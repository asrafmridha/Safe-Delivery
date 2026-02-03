<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\ParcelPaymentRequest;
use App\Notifications\MerchantParcelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ParcelPaymentRequestController extends Controller {

    public function parcelPaymentRequestList() {
        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPaymentRequestList';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse']   = 'sidebar-collapse';
        return view('admin.parcelPaymentRequest.parcelPaymentRequestList', $data);
    }

    public function getParcelPaymentRequestList(Request $request) {

        $model  = ParcelPaymentRequest::with(['merchant'])->where(function ($query) use ($request) {
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if (($request->has('status') && !is_null($status))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {

                    if ($request->has('status') && !is_null($status)) {
                        $query->where('status', $status);
                    }

                    if ($request->has('from_date') && !is_null($from_date)) {
                        $query->whereDate('date', '>=', $from_date);
                    }

                    if ($request->has('to_date') && !is_null($to_date)) {
                        $query->whereDate('date', '<=', $to_date);
                    }

                }
//                else {
//                    $query->whereDate('date', '=', date('Y-m-d'));
//                }

            })
            ->orderBy('id', 'desc')->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('merchant.company_name', function ($data) {
                $company_name = ($data->merchant->company_name) ? $data->merchant->company_name : "Merchant Company";

                return $company_name;
            })
            ->editColumn('merchant.contact_number', function ($data) {
                $contact_number = ($data->merchant->contact_number) ? $data->merchant->contact_number : "Merchant Phone";

                return $contact_number;
            })
            ->editColumn('merchant.address', function ($data) {
                $address = ($data->merchant->address) ? $data->merchant->address : "Merchant Address";

                return $address;
            })
            ->editColumn('status', function ($data) {
                $status = "";

                switch ($data->status) {
                case 1:$status = "<span class='text-bold text-warning' style='font-size:16px;'>Requested</span>";
                    break;
                case 2:$status = "<span class='text-bold text-success' style='font-size:16px;'>Accepted</span>";
                    break;
                case 3:$status = "<span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>";
                    break;
                case 4:$status = "<span class='text-bold text-primary' style='font-size:16px;'>Payment Generated</span>";
                    break;
                case 5:$status = "<span class='text-bold text-success' style='font-size:16px;'>Paid</span>";
                    break;
                default:$status = "";break;
                }

                return $status;
            })
            ->addColumn('action', function ($data) {
                $button = "";
                $button .= '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_request_id="' . $data->id . '"  title="Parcel Payment Request View">
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success payment-request-accept btn-sm" parcel_payment_request_id="' . $data->id . '" title="Parcel Payment Request Accept">
                                Accept
                            </button>';
                    $button .= '&nbsp; <button class="btn btn-danger payment-request-reject btn-sm" parcel_payment_request_id="' . $data->id . '" title="Parcel Payment Request Reject">
                                Reject
                            </button>';
                }

                if($data->status == 2) {
                    $button .= '&nbsp; <a href="'.route('admin.parcel.paymentGenerate', $data->id).'" class="btn btn-primary payment-request-generate btn-sm" parcel_payment_request_id="' . $data->id . '" title="Parcel Payment Generate">
                                Payment Generate
                            </a>';
                }

                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function viewParcelPaymentRequest(Request $request, ParcelPaymentRequest $parcelPaymentRequest) {
        $parcelPaymentRequest->load('merchant', 'merchant.branch');
        return view('admin.parcelPaymentRequest.viewParcelPaymentRequest', compact('parcelPaymentRequest'));
    }

    public function acceptPaymentRequestParcel(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_payment_request_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $data = [
                    'status' => 2,
                    'action_admin_id' =>  auth()->guard('admin')->user()->id,
                ];
                $check = ParcelPaymentRequest::where('id', $request->parcel_payment_request_id)->update($data);

                if ($check) {
                    $response = ['success' => 'Parcel Payment Request Accept Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function rejectPaymentRequestParcel(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_payment_request_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $parcelPaymentRequest = ParcelPaymentRequest::where('id', $request->parcel_payment_request_id)->first();
                Parcel::whereIn('id', json_decode($parcelPaymentRequest->parcel_ids))->update(['payment_request_status' => 0]);
                $data = [
                    'action_admin_id' =>  auth()->guard('admin')->user()->id,
                    'status' => 3,
                ];
                $check = $parcelPaymentRequest->update($data);
                if ($check) {
                    $response = ['success' => 'Parcel Payment Request Reject Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }


    /** ++++++++++++++++++++++++++++++++++++++++++
     * ===== For Merchant Parcel Payment ===== ***
     ++++++++++++++++++++++++++++++++++++++++++++*/

    public function parcelPaymentGenerate(Request $request, ParcelPaymentRequest $parcelPaymentRequest)
    {
        $admin_id = auth()->guard('admin')->user()->id;
        \Cart::session($admin_id)->clear();

        $request_data = $parcelPaymentRequest->load('merchant', 'merchant.branch');

        $data   = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPaymentRequestList';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse']   = 'sidebar-collapse';

        $data['parcelPaymentRequest']   = $request_data;

//        dd($request_data->merchant_id);

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number');
        },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('merchant_id', $request_data->merchant_id)
            ->whereRaw('delivery_type in (1,2) and payment_type in (2,4,6) ')
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id','weight_package_id',
                'customer_collect_amount', 'weight_package_charge',  'delivery_charge', 'total_charge', 'cod_charge'
            )
            ->get();
        return view('admin.parcelPaymentRequest.merchantPaymentGenerate', $data);

    }


    public function confirmParcelPaymentGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'merchant_id'           => 'required',
            'total_payment_parcel'  => 'required',
            'total_payment_amount'  => 'required',
            'date'                  => 'required',
            'note'                  => 'sometimes',
            'paid_payment_request'  => 'sometimes',
            'transfer_reference'    => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {
            $admin_id               = auth()->guard('admin')->user()->id;
            $merchant_id            = $request->input('merchant_id');
            $total_payment_amount   = $request->input('total_payment_amount');
            $merchant_payment_invoice      = $this->returnUniqueMerchantDeliveryPaymentInvoice();

            $data = [
                'merchant_payment_invoice'   => $merchant_payment_invoice,
                'admin_id'              => $admin_id,
                'merchant_id'           => $request->input('merchant_id'),
                'date_time'             => $request->input('date') . ' ' . date('H:i:s'),
                'total_payment_parcel'  => $request->input('total_payment_parcel'),
                'total_payment_amount'  => $total_payment_amount,
                'transfer_reference'    => $request->input('transfer_reference'),
                'note'                  => $request->input('note'),
                'payment_request_status'=> 1,
                'status'                => 1,
            ];
            $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::create($data);

            if ($parcelMerchantDeliveryPayment) {
                $cart = \Cart::session($admin_id)->getContent();
                $cart = $cart->sortBy('id');

                $payment_request    = ParcelPaymentRequest::where('id', $request->payment_request_id)->first()->update([
                    'parcel_merchant_delivery_payment_id' => $parcelMerchantDeliveryPayment->id,
                    'paid_payment_type' => $request->input('paid_payment_type'),
                    'status'            => 4,
                    'action_admin_id'   => $admin_id
                ]);

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    ParcelMerchantDeliveryPaymentDetail::create([
                        'parcel_merchant_delivery_payment_id'   => $parcelMerchantDeliveryPayment->id,
                        'parcel_id'                             => $parcel_id,
                        'collected_amount'                      => $item->attributes->customer_collect_amount,
                        'cod_charge'                            => $item->attributes->cod_charge,
                        'delivery_charge'                       => $item->attributes->delivery_charge,
                        'weight_package_charge'                 => $item->attributes->weight_package_charge,
                        'paid_amount'                           => $item->price,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'payment_type'              => 4,
                        'merchant_paid_amount'      => $item->price,
                    ]);


                    $parcel = Parcel::where('id', $parcel_id)->first();
                    $merchant_user = Merchant::find($merchant_id);
                    $merchant_user->notify(new MerchantParcelNotification($parcel));

//                    $this->merchantDashboardCounterEvent($merchant_id);
                }
//                $merchant     = Merchant::where('id', $merchant_id)->first();
//                $message    = "Dear ".$merchant->name.". ";
//                $message    .= "Your payment amount ".$total_payment_amount."  is successfully done.";
//                $message    .= "Your payment ID No ".$merchant_payment_invoice."   Thank you.";
//                $this->send_sms($merchant->contact_number, $message);

                \DB::commit();

//                $this->adminDashboardCounterEvent();

                $this->setMessage('Merchant Parcel Payment Insert Successfully', 'success');
                return redirect()->route('admin.parcel.merchantPaymentDeliveryList');
            }
            else{
                $this->setMessage('Merchant Parcel Payment Insert Failed', 'danger');
//                return redirect()->back()->withInput();
            }
        }
        catch (\Exception $e){
            \DB::rollback();

//            return $e->getMessage();

            $this->setMessage($e->getMessage(), 'danger');
//            $this->setMessage('Merchant Parcel Payment Insert Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function merchantDeliveryPaymentParcelAddCart(Request $request) {
        $admin_id         = auth()->guard('admin')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
        $parcels        = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->whereIn('id', $request->parcel_invoices)
//            ->whereRaw('delivery_type in (1,2) and  payment_type in (2,6) and merchant_id = ?', $request->merchant_id)
            ->whereRaw('delivery_type in (1,2) and
                            payment_type in (2,6) and
                            payment_request_status = 1 and
                            merchant_id = ?', $request->merchant_id)

            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id', 'weight_package_id',
                'customer_collect_amount', 'delivery_charge', 'weight_package_charge', 'total_charge', 'cod_charge'
            )
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($admin_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag    = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }
                if ($flag == 0) {
                    $payable_amount = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge - $parcel->cod_charge;

                    \Cart::session($admin_id)->add([
                        'id'              => $cart_id,
                        'name'            => $parcel->merchant->name,
                        'price'           => $payable_amount,
                        'quantity'        => 1,
                        'target'          => 'subtotal',
                        'attributes'      => [
                            'parcel_invoice'            => $parcel->parcel_invoice,
                            'weight_package_name'       => $parcel->weight_package->name,
                            'customer_name'             => $parcel->customer_name,
                            'customer_address'          => $parcel->customer_address,
                            'customer_contact_number'   => $parcel->customer_contact_number,
                            'merchant_name'             => $parcel->merchant->name,
                            'customer_collect_amount'   => $parcel->customer_collect_amount,
                            'weight_package_charge'     => $parcel->weight_package_charge,
                            'delivery_charge'           => $parcel->delivery_charge,
                            'cod_charge'                => $parcel->cod_charge,
                            'option'                    => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart      = \Cart::session($admin_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($admin_id)->getTotalQuantity();
            $getTotal  = \Cart::session($admin_id)->getTotal();
        }
        else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($admin_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($admin_id)->getTotalQuantity();
            $getTotal  = \Cart::session($admin_id)->getTotal();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'getTotal'  => $getTotal,
            'error'     => $error,
        ];

        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentParcelCart', $data);
    }


    public function merchantPaymentDeliveryList() {
        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPaymentGenerateList';
        $data['page_title'] = 'Parcel Payment Generate List';
        $data['collapse']   = 'sidebar-collapse';
        $data['collapse']   = 'sidebar-collapse';

        $data['merchants']   = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();
        return view('admin.parcelPaymentRequest.merchantPaymentDeliveryList', $data);
    }


    public function getMerchantPaymentDeliveryList(Request $request) {

        $model = ParcelMerchantDeliveryPayment::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        },])->where(function ($query) use ($request) {
                $merchant_id = $request->input('merchant_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if(($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0)
                    || ($request->has('status') && !is_null($status) && $status != '' && $status != 0)
                    || ($request->has('from_date') && !is_null($from_date) && $from_date != '')
                    || ($request->has('to_date') && !is_null($to_date) && $to_date != '')
                ){
                    if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                        $query->where('merchant_id', $request->input('merchant_id'));
                    }

                    if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
                        $query->where('status', $request->input('status'));
                    }


                    if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                        $query->whereDate('date_time', '>=', $request->input('from_date'));
                    }

                    if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                        $query->whereDate('date_time', '<=', $request->input('to_date'));
                    }
                }
                else{
//                    $query->whereDate('date_time', '>=', date('Y-m-d'));
//                    $query->whereDate('date_time', '<=', date('Y-m-d'));
//                    $query->where('payment_request_status', 1);
//                    $query->where('status', '!=', '3');
                }
            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date_time', function ($data) {
                return date('d-m-Y H:i:s', strtotime($data->date_time));
            })
            ->editColumn('total_payment_amount', function ($data) {
                return number_format($data->total_payment_amount, 2);
            })
            ->editColumn('total_payment_received_amount', function ($data) {
                return number_format($data->total_payment_received_amount, 2);
            })
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1:$status_name  = "Payment Request"; $class  = "success";break;
                    case 2:$status_name  = "Payment Accept"; $class  = "success";break;
                    case 3:$status_name  = "Payment Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Delivery Payment">
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" data-toggle="modal" data-target="#printModal" parcel_delivery_payment_id="' . $data->id . '" title="Print Delivery Payment">
                <i class="fa fa-print"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success merchant-delivery-payment-accept btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Confirmed Merchant Delivery Payment">
                    <i class="fa fa-check"></i>  </button>';

//                    $button .= '&nbsp; <a href="' . route('admin.account.merchantPaymentDeliveryGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Merchant Delivery Payment " >
//                        <i class="fas fa-edit"></i> </a>';

                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" merchant_payment_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }


    public function viewMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.parcelPaymentRequest.viewMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }

    public function printMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.parcelPaymentRequest.printMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }


    /** For Merchant Payment Confirmed */
    public function merchantDeliveryPaymentAccept(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.parcelPaymentRequest.merchantDeliveryPaymentAccept', compact('parcelMerchantDeliveryPayment'));
    }


    public function merchantDeliveryPaymentAcceptConfirm(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {

        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note' => 'sometimes',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check =  0;
                \DB::beginTransaction();
                try {
                    $data = [
                        'total_payment_received_parcel'      => $parcelMerchantDeliveryPayment->total_payment_parcel,
                        'total_payment_received_amount'      => $parcelMerchantDeliveryPayment->total_payment_amount,
                        'note'      => $request->note,
                        'status'      => 2,
                        'action_date_time' => date('Y-m-d H:i:s'),
                    ];
                    $check = ParcelMerchantDeliveryPayment::where('id',$parcelMerchantDeliveryPayment->id)->update($data);

                    $payment_request    = ParcelPaymentRequest::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->first();

                    if ($check) {

                        $ParcelMerchantDeliveryPaymentDetails =  ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->get();


                        foreach($ParcelMerchantDeliveryPaymentDetails as $ParcelMerchantDeliveryPaymentDetail){
                            Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->update([
                                'payment_type' => 5
                            ]);

                            $parcel = Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->first();
                            $merchant_user = Merchant::find($parcel->merchant_id);
                            $merchant_user->notify(new MerchantParcelNotification($parcel));
                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        }

                        $payment_request->update([
                            'status'    => 5,
                            'action_admin_id'   => auth('admin')->user()->id
                        ]);

                        ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id',$parcelMerchantDeliveryPayment->id)
                            ->update([
                                'status'      => 2,
                                'date_time'   => date('Y-m-d H:i:s'),
                            ]);



                        \DB::commit();

                        /** For SMS */
                        $merchant     = Merchant::where('id', $parcelMerchantDeliveryPayment->merchant_id)->first();
                        $message    = "Dear ".$merchant->name.". ";
                        $message    .= "Your payment amount ".$parcelMerchantDeliveryPayment->total_payment_amount."  is successfully done.";
                        $message    .= "Your payment ID No ".$parcelMerchantDeliveryPayment->merchant_payment_invoice."   Thank you.";
                      //  $this->send_sms($merchant->contact_number, $message);

                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Payment Confirmed Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }





    public function merchantPaymentDeliveryDelete(Request $request)
    {
        $response = ['error' => 'Error Found 1'];

        if($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'parcel_delivery_payment_id' => 'required',
            ]);

            if($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            }else{

                $merchantDeliveryPayment    = ParcelMerchantDeliveryPayment::where('id', $request->get('parcel_delivery_payment_id'))->first();
                $merchantDeliveryPaymentDetail = $merchantDeliveryPayment->parcel_merchant_delivery_payment_details;
                $parcel_ids = [];
                if($merchantDeliveryPaymentDetail) {
                    foreach ($merchantDeliveryPaymentDetail as $mpdetail) {

                        $parcel_ids[]   = $mpdetail->parcel_id;
                    }
                }

                $merchant_id = $merchantDeliveryPayment->merchant_id;

                $payment_request    = ParcelPaymentRequest::where('parcel_merchant_delivery_payment_id', $request->get('parcel_delivery_payment_id'))->first();

                \DB::beginTransaction();
                try{


                    $merchantDeliveryPayment->parcel_merchant_delivery_payment_details()->delete();
                    $merchantDeliveryPayment->delete();

                    if($payment_request){
                        $payment_request->update([
                            'status'    => 2,
                            'action_admin_id' => auth('admin')->user()->id
                        ]);
                    }

                    Parcel::whereIn('id', $parcel_ids)->update([
                        'payment_type'              => 2,
                        'merchant_paid_amount'      => 0,
                    ]);

                    \DB::commit();

                    $this->merchantDashboardCounterEvent($merchant_id);
                    $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Merchant Delivery Payment Delete Successfully!'];
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => $e->getMessage()];
//                    $response = ['error' => 'Database error found!'];
                }

            }
        }
        return $response;
    }


    /** Print Booking Parcel List */
    public function printMerchantDeliveryPaymentList(Request $request){

        $merchantDeliveryPaymentList = ParcelMerchantDeliveryPayment::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
        ])
            ->where(function ($query) use ($request) {
                $merchant_id = $request->input('merchant_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if(($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0)
                    || ($request->has('status') && !is_null($status) && $status != '' && $status != 0)
                    || ($request->has('from_date') && !is_null($from_date) && $from_date != '')
                    || ($request->has('to_date') && !is_null($to_date) && $to_date != '')
                ){
                    if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                        $query->where('merchant_id', $request->input('merchant_id'));
                    }

                    if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
                        $query->where('status', $request->input('status'));
                    }


                    if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                        $query->whereDate('date_time', '>=', $request->input('from_date'));
                    }

                    if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                        $query->whereDate('date_time', '<=', $request->input('to_date'));
                    }
                }
                else{
//                    $query->whereDate('date_time', '>=', date('Y-m-d'));
//                    $query->whereDate('date_time', '<=', date('Y-m-d'));
                    $query->where('payment_request_status', 1);
                    $query->where('status', '!=', '3');
                }


            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.parcelPaymentRequest.printMerchantDeliveryPaymentList', compact('merchantDeliveryPaymentList'));
    }

}

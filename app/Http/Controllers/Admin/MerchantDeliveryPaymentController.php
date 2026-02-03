<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\ParcelPaymentRequest;
use App\Notifications\MerchantParcelNotification;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPaymentDetail;

class MerchantDeliveryPaymentController extends Controller
{


    public function merchantPaymentDeliveryList()
    {
        $data = [];
        $data['main_menu'] = 'merchant-payment';
        $data['child_menu'] = 'merchantPaymentDeliveryList';
        $data['page_title'] = 'Merchant Delivery Payment List';
        $data['collapse'] = 'sidebar-collapse';
        $data['merchants'] = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();
        return view('admin.account.merchantDeliveryPayment.merchantPaymentDeliveryList', $data);
    }


    public function getMerchantPaymentDeliveryList(Request $request)
    {

        $model = ParcelMerchantDeliveryPayment::with(['parcel_merchant_delivery_payment_details.parcel','merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number', 'address');
        },
        ])
            ->where(function ($query) use ($request) {
                $merchant_id = $request->input('merchant_id');
                $status = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                if (($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0)
                    || ($request->has('status') && !is_null($status) && $status != '' && $status != 0)
                    || ($request->has('from_date') && !is_null($from_date) && $from_date != '')
                    || ($request->has('to_date') && !is_null($to_date) && $to_date != '')
                ) {
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
                } else {
                    // $query->whereDate('date_time', '>=', date('Y-m-d'));
                    // $query->whereDate('date_time', '<=', date('Y-m-d'));
                    $query->where('status', '!=', '3');
                }


            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date_time', function ($data) {
                return date('d-m-Y', strtotime($data->date_time));
            })
            ->editColumn('total_payment_amount', function ($data) {
                return number_format($data->total_payment_amount, 2);
            })
            ->editColumn('total_payment_received_amount', function ($data) {
                return number_format($data->total_payment_received_amount, 2);
            })
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1:
                        $status_name = "Payment Request";
                        $class = "success";
                        break;
                    case 2:
                        $status_name = "Payment Accept";
                        $class = "success";
                        break;
                    case 3:
                        $status_name = "Payment Reject";
                        $class = "danger";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Merchant Delivery Payment">
                <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <a href="' . route('admin.account.printMerchantDeliveryPayment', $data->id) . '" class="btn btn-success btn-sm" title="Print Merchant Delivery Payment" target="_blank">
                <i class="fas fa-print"></i> </a>';
                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success merchant-delivery-payment-accept btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Confirmed Merchant Delivery Payment">
                    <i class="fa fa-check"></i>  </button>';

                    $button .= '&nbsp; <a href="' . route('admin.account.merchantPaymentDeliveryGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Merchant Delivery Payment " >
                        <i class="fas fa-edit"></i> </a>';

                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" merchant_payment_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
           ->addColumn('total_collect_amount', function ($data) {
    $total_collect_amount = 0;
    foreach ($data->parcel_merchant_delivery_payment_details as $v_data) {
        $total_collect_amount += optional($v_data->parcel)->total_collect_amount ?? 0;
    }
    return $total_collect_amount;
    })

           ->addColumn('customer_collect_amount', function ($data) {
    $customer_collect_amount = 0;
    foreach ($data->parcel_merchant_delivery_payment_details as $v_data) {
        $customer_collect_amount += optional($v_data->parcel)->customer_collect_amount ?? 0;
    }
    return $customer_collect_amount;
})

           ->addColumn('total_charge', function ($data) {
    $total_charge = 0;
    foreach ($data->parcel_merchant_delivery_payment_details as $v_data) {
        $total_charge += optional($v_data->parcel)->total_charge ?? 0;
    }
    return number_format($total_charge, 2);
})

            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time', 'total_collect_amount','customer_collect_amount','total_charge'])
            ->make(true);
    }


    public function viewMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.account.merchantDeliveryPayment.viewMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }

    public function printMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.account.merchantDeliveryPayment.printMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }


    /** For Merchant Payment Confirmed */
    public function merchantDeliveryPaymentAccept(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {
        $inputs=[
            "merchant_id" => $request->input('merchant_id'),
            "status" => $request->input('status'),
            "from_date" => $request->input('from_date'),
            "to_date" => $request->input('to_date'),
        ];
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentAccept', compact('parcelMerchantDeliveryPayment','inputs'));
    }

    public function merchantDeliveryPaymentAcceptConfirm(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note' => 'sometimes',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {
                    $data = [
                        'total_payment_received_parcel' => $parcelMerchantDeliveryPayment->total_payment_parcel,
                        'total_payment_received_amount' => $parcelMerchantDeliveryPayment->total_payment_amount,
                        'note' => $request->note,
                        'status' => 2,
                        'action_date_time' => date('Y-m-d H:i:s'),
                    ];
                    $check = ParcelMerchantDeliveryPayment::where('id', $parcelMerchantDeliveryPayment->id)->update($data);
                    $payment_request    = ParcelPaymentRequest::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->first();
                    if ($check) {

                        $ParcelMerchantDeliveryPaymentDetails = ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->get();

                        //dd($parcelMerchantDeliveryPayment->id, $ParcelMerchantDeliveryPaymentDetails);

                        foreach ($ParcelMerchantDeliveryPaymentDetails as $ParcelMerchantDeliveryPaymentDetail) {
                            Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->update([
                                'payment_type' => 5
                            ]);

                            $parcel = Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->first();
                            $merchant_user = Merchant::find($parcel->merchant_id);
                            $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        }
                        if ($payment_request){
                            $payment_request->update([
                                'status'    => 5,
                                'action_admin_id'   => auth('admin')->user()->id
                            ]);
                        }
                        ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)
                            ->update([
                                'status' => 2,
                                'date_time' => date('Y-m-d H:i:s'),
                            ]);

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Payment Confirmed Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => $e];
                }
            }
        }
        return response()->json($response);
    }


    public function merchantPaymentDeliveryGenerate()
    {
        $admin_id = auth()->guard('admin')->user()->id;
        \Cart::session($admin_id)->clear();
        $data = [];
        $data['main_menu'] = 'merchant-payment';
        $data['child_menu'] = 'merchantPaymentDeliveryGenerate';
        $data['page_title'] = 'Merchant Payment Delivery';
        $data['collapse'] = 'sidebar-collapse';
        $data['merchants'] = Merchant::whereHas('parcel', function ($query) {
            $query->whereRaw("
                                    ((parcels.delivery_type in (1) AND parcels.payment_type in (2,6))
                                    OR (parcels.delivery_type in (2) AND parcels.payment_type in (2,6) AND parcels.status >= 25)
                                    OR (parcels.delivery_type in (4)  AND (parcels.payment_type is NULL || parcels.payment_type in (2,6)) AND parcels.status = 36))
                                ");
        })
            ->get();


        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number');
        },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }])
            ->whereRaw("
                ((parcels.delivery_type in (1) AND parcels.payment_type IN (2,6))
                OR (parcels.delivery_type in (2) AND parcels.payment_type IN (2,6) AND parcels.status >= 25)
                OR (parcels.delivery_type in (4) AND (parcels.payment_type is NULL || parcels.payment_type in (2,6)) AND parcels.status = 36))
            ")
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id', 'weight_package_id',
                'customer_collect_amount', 'weight_package_charge', 'delivery_charge',
                'delivery_type', 'merchant_service_area_return_charge',
                'total_charge', 'cod_charge'
            )
            ->get();
        return view('admin.account.merchantDeliveryPayment.merchantPaymentDeliveryGenerate', $data);
    }


    public function merchantDeliveryPaymentParcelClearCart()
    {
        $admin_id = auth()->guard('admin')->user()->id;
        \Cart::session($admin_id)->clear();
        $admin_id = auth()->guard('admin')->user()->id;
        $cart = \Cart::session($admin_id)->getContent();
        $cart = $cart->sortBy('id');
        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($admin_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($admin_id)->getTotal(),
            'error' => "",
        ];
        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentParcelCart', $data);
    }


    public function returnMerchantDeliveryPaymentParcel(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number');
        },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->where(function ($query) use ($request) {
                if (!empty($request->parcel_invoice)) {
                    $query->whereRaw('((delivery_type in (1) and payment_type in (2,6))
                    OR (delivery_type in (2) and payment_type in (2,6) and status >= 25)
                    OR (delivery_type in (4) and (parcels.payment_type is NULL || parcels.payment_type in (2,6))  and status = 36)) and merchant_id = ? and parcel_invoice = ? ', [$request->merchant_id, $request->parcel_invoice]);
                } elseif (!empty($request->merchant_order_id)) {
                    $query->whereRaw('((delivery_type in (1) and payment_type in (2,6))
                    OR (delivery_type in (2) and payment_type in (2,6) and status >= 25)
                    OR (delivery_type in (4) and (parcels.payment_type is NULL || parcels.payment_type in (2,6)) and status = 36)) and merchant_id = ? and merchant_order_id = ? ', [$request->merchant_id, $request->merchant_order_id]);
                } else {
                    $query->whereRaw('((delivery_type in (1) and payment_type in (2,6))
                    OR (delivery_type in (2) and payment_type in (2,6) and status >= 25)
                    OR (delivery_type in (4) and (parcels.payment_type is NULL || parcels.payment_type in (2,6)) and status = 36))  and merchant_id = ?', [$request->merchant_id]);
                }
            })
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id', 'weight_package_id',
                'customer_collect_amount', 'delivery_charge', 'weight_package_charge', 'total_charge', 'cod_charge'
            )
            ->get();

        return view('admin.account.merchantDeliveryPayment.returnMerchantDeliveryPaymentParcel', $data);
    }


    public function merchantDeliveryPaymentParcelAddCart(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $merchant_id = $request->input('merchant_id');
        $parcel_invoice = $request->input('parcel_invoice');
        $parcel_return_charges = $request->input('parcel_return_charges');
        $parcel_cod_charges = $request->input('parcel_cod_charges');
        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->whereRaw("
                    ((delivery_type in (1) and payment_type in (2,6))
                    OR (delivery_type in (2) and payment_type in (2,6) and status >= 25)
                    OR (delivery_type in (4) and (parcels.payment_type is NULL || parcels.payment_type in (2,6)) and status = 36))
                ")
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id', 'weight_package_id',
                'customer_collect_amount', 'delivery_charge',
                'delivery_type', 'merchant_service_area_return_charge',
                'weight_package_charge', 'total_charge', 'cod_charge'
            )
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($admin_id)->getContent();
            $cart = $cart->sortBy('id');

            $sl = 0;

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;
                if ($merchant_id == $parcel->merchant_id) {
                    if (count($cart) > 0) {
                        foreach ($cart as $item) {
                            if ($cart_id == $item->id) {
                                $flag++;
                            }
                        }
                    }
                    if ($flag == 0) {
                        $returnCharge = 0;
                        if ($parcel->delivery_type == 4 || $parcel->delivery_type == 2) {
                            $returnCharge = $parcel_return_charges[$sl];
                        }
                        $payable_amount = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge - $parcel_cod_charges[$sl] - $returnCharge;
    
                        \Cart::session($admin_id)->add([
                            'id' => $cart_id,
                            'name' => $parcel->merchant->name,
                            'price' => $payable_amount,
                            'quantity' => 1,
                            'target' => 'subtotal',
                            'attributes' => [
                                'parcel_invoice' => $parcel->parcel_invoice,
                                'weight_package_name' => $parcel->weight_package->name,
                                'customer_name' => $parcel->customer_name,
                                'customer_address' => $parcel->customer_address,
                                'customer_contact_number' => $parcel->customer_contact_number,
                                'merchant_name' => $parcel->merchant->name,
                                'customer_collect_amount' => $parcel->customer_collect_amount,
                                'weight_package_charge' => $parcel->weight_package_charge,
                                'delivery_charge' => $parcel->delivery_charge,
                                'cod_charge' => $parcel_cod_charges[$sl],
                                'return_charge' => $parcel_return_charges[$sl],
                                'option' => [],
                            ],
                            'associatedModel' => $parcel,
                        ]);
                    }
                }

                $sl++;
            }

            $error = "";

            $cart = \Cart::session($admin_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($admin_id)->getTotalQuantity();
            $getTotal = \Cart::session($admin_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($admin_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($admin_id)->getTotalQuantity();
            $getTotal = \Cart::session($admin_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];


        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentParcelCart', $data);
    }


    public function merchantDeliveryPaymentParcelDeleteCart(Request $request)
    {
        $admin_id = auth()->guard('admin')->user()->id;
        \Cart::session($admin_id)->remove($request->input('itemId'));
        $cart = \Cart::session($admin_id)->getContent();
        $cart = $cart->sortBy('id');
        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($admin_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($admin_id)->getTotal(),
            'error' => "",
        ];
        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentParcelCart', $data);
    }


    public function confirmMerchantDeliveryPaymentGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'total_payment_parcel' => 'required',
            'total_payment_amount' => 'required',
            'date' => 'required',
            'note' => 'sometimes',
            'transfer_reference' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {
            $admin_id = auth()->guard('admin')->user()->id;
            $merchant_id = $request->input('merchant_id');
            $total_payment_amount = $request->input('total_payment_amount');
            $merchant_payment_invoice = $this->returnUniqueMerchantDeliveryPaymentInvoice();

            $data = [
                'merchant_payment_invoice' => $merchant_payment_invoice,
                'admin_id' => $admin_id,
                'merchant_id' => $request->input('merchant_id'),
                'date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_payment_parcel' => $request->input('total_payment_parcel'),
                'total_payment_amount' => $total_payment_amount,
                'transfer_reference' => $request->input('transfer_reference'),
                'note' => $request->input('note'),
                'status' => 1,
            ];

            $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::create($data);

            if ($parcelMerchantDeliveryPayment) {
                $cart = \Cart::session($admin_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    ParcelMerchantDeliveryPaymentDetail::create([
                        'parcel_merchant_delivery_payment_id' => $parcelMerchantDeliveryPayment->id,
                        'parcel_id' => $parcel_id,
                        'collected_amount' => $item->attributes->customer_collect_amount,
                        'cod_charge' => $item->attributes->cod_charge,
                        'delivery_charge' => $item->attributes->delivery_charge,
                        'weight_package_charge' => $item->attributes->weight_package_charge,
                        'return_charge' => $item->attributes->return_charge,
                        'paid_amount' => $item->price,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'payment_type' => 4,
                        'return_charge' => $item->attributes->return_charge,
                        'cod_charge' => $item->attributes->cod_charge,
                        'merchant_paid_amount' => $item->price,
                    ]);


                    $parcel = Parcel::where('id', $parcel_id)->first();
                    $merchant_user = Merchant::find($merchant_id);
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));
                    // $this->merchantDashboardCounterEvent($merchant_id);
                }
                $merchant = Merchant::where('id', $merchant_id)->first();
                $message = "Dear " . $merchant->name . ". ";
                $message .= "Your payment amount " . $total_payment_amount . "  is successfully done.";
                $message .= "Your payment ID No " . $merchant_payment_invoice . "   Thank you.";
             //   $this->send_sms($merchant->contact_number, $message);

                \DB::commit();

                $this->adminDashboardCounterEvent();

                $this->setMessage('Merchant Delivery Payment Insert Successfully', 'success');
                return redirect()->route('admin.account.merchantPaymentDeliveryList');
            } else {
                $this->setMessage('Merchant Delivery Payment Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage($e, 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function merchantPaymentDeliveryGenerateEdit(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {

        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');

        $admin_id = auth()->guard('admin')->user()->id;
        \Cart::session($admin_id)->clear();

        foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $parcel_merchant_delivery_payment_detail) {
            $cart_id = $parcel_merchant_delivery_payment_detail->parcel->id;

            \Cart::session($admin_id)->add([
                'id' => $cart_id,
                'name' => $parcelMerchantDeliveryPayment->merchant->name,
                'price' => $parcel_merchant_delivery_payment_detail->paid_amount,
                'quantity' => 1,
                'target' => 'subtotal',
                'attributes' => [
                    'parcel_invoice' => $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice,
                    'weight_package_name' => $parcel_merchant_delivery_payment_detail->parcel->weight_package->name,
                    'customer_name' => $parcel_merchant_delivery_payment_detail->parcel->customer_name,
                    'customer_address' => $parcel_merchant_delivery_payment_detail->parcel->customer_address,
                    'customer_contact_number' => $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number,
                    'merchant_name' => $parcel_merchant_delivery_payment_detail->parcel->merchant->name,
                    'customer_collect_amount' => $parcel_merchant_delivery_payment_detail->collected_amount,
                    'delivery_charge' => $parcel_merchant_delivery_payment_detail->cod_charge,
                    'cod_charge' => $parcel_merchant_delivery_payment_detail->delivery_charge,
                    'option' => [],
                ],
                'associatedModel' => $parcel_merchant_delivery_payment_detail->parcel,
            ]);
        }

        $data = [];
        $cart = \Cart::session($admin_id)->getContent();
        $data['cart'] = $cart->sortBy('id');
        $data['totalItem'] = \Cart::session($admin_id)->getTotalQuantity();
        $data['getTotal'] = \Cart::session($admin_id)->getTotal();
        $data['main_menu'] = 'account';
        $data['child_menu'] = 'merchantPaymentDeliveryGenerate';
        $data['page_title'] = 'Merchant Payment Delivery Edit';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcelMerchantDeliveryPayment'] = $parcelMerchantDeliveryPayment;
        $data['merchants'] = Merchant::where('status', 1)
            ->whereHas('parcel', function ($query) {
                $query->whereRaw('delivery_type in (1,2) and payment_type = 2 ');
            })
            ->get();

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number');
        }, 'weight_package' => function ($query) {
            $query->select('id', 'name');
        }])
            ->whereRaw('delivery_type in (1,2) and payment_type = 2')
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name',
                'customer_contact_number', 'merchant_id', 'weight_package_id',
                'customer_collect_amount', 'delivery_charge', 'total_charge', 'cod_charge'
            )
            ->get();

        return view('admin.account.merchantDeliveryPayment.merchantPaymentDeliveryGenerateEdit', $data);
    }


    public function confirmMerchantPaymentDeliveryGenerateEdit(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment)
    {

        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'total_payment_parcel' => 'required',
            'total_payment_amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {
            $admin_id = auth()->guard('admin')->user()->id;

            $data = [
                'admin_id' => $admin_id,
                'merchant_id' => $request->input('merchant_id'),
                'date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_payment_parcel' => $request->input('total_payment_parcel'),
                'total_payment_amount' => $request->input('total_payment_amount'),
                'transfer_reference' => $request->input('transfer_reference'),
                'note' => $request->input('note'),
                'status' => 1,
            ];
            $check = ParcelMerchantDeliveryPayment::where('id', $parcelMerchantDeliveryPayment->id)->update($data);

            if ($check) {
                $cart = \Cart::session($admin_id)->getContent();
                $cart = $cart->sortBy('id');


                $parcelMerchantDeliveryPaymentDetails = ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->get();
                foreach ($parcelMerchantDeliveryPaymentDetails as $parcelMerchantDeliveryPaymentDetail) {
                    Parcel::where('id', $parcelMerchantDeliveryPaymentDetail->parcel_id)->update([
                        'payment_type' => 2,
                        'merchant_paid_amount' => 0,
                    ]);
                }
                ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->delete();


                foreach ($cart as $item) {


                    $parcel_id = $item->id;
                    ParcelMerchantDeliveryPaymentDetail::create([
                        'parcel_merchant_delivery_payment_id' => $parcelMerchantDeliveryPayment->id,
                        'parcel_id' => $parcel_id,
                        'collected_amount' => $item->attributes->customer_collect_amount,
                        'cod_charge' => $item->attributes->cod_charge,
                        'delivery_charge' => $item->attributes->delivery_charge,
                        'paid_amount' => $item->price,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'payment_type' => 4,
                        'merchant_paid_amount' => $item->price,
                    ]);

                    $parcel = Parcel::where('id', $parcel_id)->first();
                    $merchant_user = Merchant::find($parcel->merchant_id);
                    $merchant_user->notify(new MerchantParcelNotification($parcel));

                    $this->merchantDashboardCounterEvent($parcel->merchant_id);
                }

                \DB::commit();

                $this->adminDashboardCounterEvent();

                $this->setMessage('Merchant Delivery Payment Update Successfully', 'success');
                return redirect()->route('admin.account.merchantPaymentDeliveryList');
            } else {
                $this->setMessage('Merchant Delivery Payment Update Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Delivery Payment Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function merchantPaymentDeliveryDelete(Request $request)
    {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'parcel_delivery_payment_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            } else {

                $merchantDeliveryPayment = ParcelMerchantDeliveryPayment::where('id', $request->get('parcel_delivery_payment_id'))->first();
                $merchantDeliveryPaymentDetail = $merchantDeliveryPayment->parcel_merchant_delivery_payment_details;
                $parcel_ids = [];
                if ($merchantDeliveryPaymentDetail) {
                    foreach ($merchantDeliveryPaymentDetail as $mpdetail) {

                        $parcel_ids[] = $mpdetail->parcel_id;
                    }
                }

                $merchant_id = $merchantDeliveryPayment->merchant_id;
                //dd($merchantDeliveryPayment, $parcel_ids);

                \DB::beginTransaction();
                try {


                    $merchantDeliveryPayment->parcel_merchant_delivery_payment_details()->delete();
                    $merchantDeliveryPayment->delete();
                    Parcel::whereIn('id', $parcel_ids)->update([
                        'payment_type' => 2,
                        'merchant_paid_amount' => 0,
                    ]);

                    \DB::commit();

                    $this->merchantDashboardCounterEvent($merchant_id);
                    $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Merchant Delivery Payment Delete Successfully!'];
                } catch (\Exception $e) {
                    \DB::rollback();
//                    $response = ['error' => $e->getMessage()];
                    $response = ['error' => 'Database error found!'];
                }

            }
        }
        return $response;
    }


    public function merchantPaymentDeliveryStatement()
    {
        $data = [];
        $data['main_menu'] = 'merchant-payment';
        $data['child_menu'] = 'merchantPaymentStatement';
        $data['page_title'] = 'Merchant Delivery Payment Statement';
        $data['collapse'] = 'sidebar-collapse';
        $data['merchants'] = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();

        $from_date =  Carbon::now()->subDays(1)->format("Y-m-d");
        $to_date = Carbon::now()->addDays(1)->format("Y-m-d");

        $model = ParcelMerchantDeliveryPaymentDetail::whereBetween('created_at', [$from_date, $to_date])->get();

        $data['date_array'] = array();
        $data['transaction_ids'] = array();
        $data['merchant_payment_data'] = array();

        if (count($model) > 0) {
            foreach ($model as $dpayment) {
                $payment_date = date("Y-m-d", strtotime($dpayment->created_at));
                $transaction_id = $dpayment->parcel_merchant_delivery_payment->merchant_payment_invoice;
                $data['date_array'][$payment_date][] = $dpayment->id;
                $data['transaction_ids'][$transaction_id][] = $dpayment->parcel->parcel_invoice;
            }

            $data['merchant_payment_data'] = $model;
        }
        return view('admin.account.merchantDeliveryPayment.merchantDeliveryPaymentStatement', $data);
    }


    public function getMerchantPaymentDeliveryStatement(Request $request)
    {

        $data = [];
        $model = ParcelMerchantDeliveryPaymentDetail::with(['parcel', 'parcel_merchant_delivery_payment'])
            ->where(function ($query) use ($request) {
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                $merchant_id = $request->input('merchant_id');
                if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                    $query->whereHas('parcel_merchant_delivery_payment', function ($query) use ($merchant_id) {
                        $query->where('merchant_id', $merchant_id);
                    });
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('created_at', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('created_at', '<=', $request->input('to_date'));
                }


            })->get();

        $data['date_array'] = array();
        $data['transaction_ids'] = array();
        $data['merchant_payment_data'] = array();

        if (count($model) > 0) {
            foreach ($model as $dpayment) {
                $payment_date = date("Y-m-d", strtotime($dpayment->created_at));
                $transaction_id = $dpayment->parcel_merchant_delivery_payment->merchant_payment_invoice;
                $data['date_array'][$payment_date][] = $dpayment->id;
                $data['transaction_ids'][$transaction_id][] = $dpayment->parcel->parcel_invoice;
            }

            $data['merchant_payment_data'] = $model;
        }

        return view('admin.account.merchantDeliveryPayment.filterMerchantDeliveryPaymentStatement', $data);

    }

    public function printMerchantPaymentDeliveryStatement(Request $request)
    {

        $data = [];
        $filter = array();
        $model = ParcelMerchantDeliveryPaymentDetail::with(['parcel', 'parcel_merchant_delivery_payment'])
            ->where(function ($query) use ($request) {
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                $merchant_id = $request->input('merchant_id');
                if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                    $query->whereHas('parcel_merchant_delivery_payment', function ($query) use ($merchant_id) {
                        $query->where('merchant_id', $merchant_id);
                    });
                }
                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('created_at', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('created_at', '<=', $request->input('to_date'));
                }
            })->get();

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $merchant_id = $request->input('merchant_id');
        if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
            $filter['merchant_id'] = $merchant_id;
        }
        if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
            $filter['to_date'] = $request->get('to_date');
        }
        $date_array = array();
        $transaction_ids = array();
        $merchant_payment_data = array();

        if (count($model) > 0) {
            foreach ($model as $dpayment) {
                $payment_date = date("Y-m-d", strtotime($dpayment->created_at));
                $transaction_id = $dpayment->parcel_merchant_delivery_payment->merchant_payment_invoice;
                $date_array[$payment_date][] = $dpayment->id;
                $transaction_ids[$transaction_id][] = $dpayment->parcel->parcel_invoice;
            }

            $merchant_payment_data = $model;
        }
        return view('admin.account.merchantDeliveryPayment.printMerchantDeliveryPaymentStatement', compact('merchant_payment_data', 'filter','date_array','transaction_ids'));

    }


}

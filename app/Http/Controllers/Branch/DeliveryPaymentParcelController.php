<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelDeliveryPaymentDetail;

class DeliveryPaymentParcelController extends Controller
{


    public function deliveryPaymentList()
    {
        $data = [];
        $data['main_menu'] = 'completeDeliveryParcel';
        $data['child_menu'] = 'deliveryPaymentList';
        $data['page_title'] = 'Delivery Payment List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.deliveryPayment.deliveryPaymentList', $data);
    }


    public function getDeliveryPaymentList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $model = ParcelDeliveryPayment::with(['admin'
        => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->whereRaw('branch_id = ?', [$branch_id])
            ->where(function ($query) use ($request) {
                $status = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                if ($request->has('status') && !is_null($status) && $status != '') {
                    $query->where('status', $request->get('status'));
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('date_time', '>=', $request->get('from_date'));
                }
                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('date_time', '<=', $request->get('to_date'));
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
                    case 1 :
                        $status_name = "Send Request";
                        $class = "success";
                        break;
                    case 2 :
                        $status_name = "Accept";
                        $class = "success";
                        break;
                    case 3 :
                        $status_name = "Reject";
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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" parcel_delivery_payment_id="' . $data->id . '" title="Print Delivery Payment" >
                <i class="fa fa-print"></i> </button>';
                if ($data->status == 1) {
                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryPaymentGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Delivery Payment " >
                        <i class="fas fa-edit"></i> </a>';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }


    public function printDeliveryPaymentList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        $filter = [];

        $model = ParcelDeliveryPayment::with(['admin'
        => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->whereRaw('branch_id = ?', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $status = $request->input('status');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        if ($request->has('status') && !is_null($status) && $status != '') {
            $model->where('status', $request->get('status'));
            $filter['status'] = $request->get('status');
        }

        if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
            $model->whereDate('date_time', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
            $model->whereDate('date_time', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        $parcelDeliveryPayments = $model->get();
        return view('branch.parcel.deliveryPayment.printDeliveryPaymentList', compact('parcelDeliveryPayments', 'filter'));
    }


    public function viewDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        // dd($parcelDeliveryPayment);
        return view('branch.parcel.deliveryPayment.viewDeliveryPayment', compact('parcelDeliveryPayment'));
    }
    public function printDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        // dd($parcelDeliveryPayment);
        return view('branch.parcel.deliveryPayment.printDeliveryPayment', compact('parcelDeliveryPayment'));
    }


    public function deliveryPaymentGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'completeDeliveryParcel';
        $data['child_menu'] = 'deliveryPaymentGenerate';
        $data['page_title'] = 'Delivery Payment';
        $data['collapse'] = 'sidebar-collapse';

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number');
        },])
            // ->whereRaw('delivery_branch_id = ? and (delivery_type = 1 OR (delivery_type = 2  AND status >= 25)) and (payment_type is null OR payment_type = 3)', [$branch_id])
             ->whereRaw('delivery_branch_id = ? and ((delivery_type = 1 AND status >= 25) OR (delivery_type = 2  AND status >= 25)) and (payment_type is null OR payment_type = 3)', [$branch_id])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
            ->get();

        return view('branch.parcel.deliveryPayment.deliveryPaymentGenerate', $data);
    }


    public function returnDeliveryPaymentParcel(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice_barcode) || !empty($parcel_invoice) || !empty($merchant_order_id)) {

            $data['parcels'] = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number');
            },
            ])
                ->whereRaw('delivery_branch_id = ? and  (delivery_type = 1 OR ( delivery_type = 2  AND status >= 25) and payment_type = null)', [$branch_id])
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {
                    if (!empty($parcel_invoice)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice,
                        ]);
                    } elseif (!empty($merchant_order_id)) {
                        $query->where([
                            'merchant_order_id' => $merchant_order_id,
                        ]);
                    }
                })
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
                ->get();
        } else {
            $data['parcels'] = [];
        }

        $parcels = $data['parcels'];
        return view('branch.parcel.deliveryPayment.deliveryPaymentParcel', $data);
    }


    public function deliveryPaymentParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');


        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->whereRaw('delivery_branch_id = ? and  delivery_type in (1,2) and (delivery_type = 1 OR (delivery_type = 2  AND status >= 25))  and (payment_type is null OR payment_type = 3)', [$branch_id])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
            ->get();
        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->merchant->name,
                        'price' => $parcel->customer_collect_amount,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'customer_collect_amount' => $parcel->customer_collect_amount,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.deliveryPayment.deliveryPaymentParcelCart', $data);
    }


    public function deliveryPaymentParcelDeleteCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.parcel.deliveryPayment.deliveryPaymentParcelCart', $data);
    }


    public function confirmDeliveryPaymentGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_payment_parcel' => 'required',
            'total_payment_amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {

            $data = [
                'payment_invoice' => $this->returnUniqueDeliveryPaymentInvoice(),
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_payment_parcel' => $request->input('total_payment_parcel'),
                'total_payment_amount' => $request->input('total_payment_amount'),
                'note' => $request->input('note'),
                'status' => 1,
            ];
            $parcelDeliveryPayment = ParcelDeliveryPayment::create($data);

            if ($parcelDeliveryPayment) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    ParcelDeliveryPaymentDetail::create([
                        'parcel_delivery_payment_id' => $parcelDeliveryPayment->id,
                        'parcel_id' => $parcel_id,
                        'amount' => $item->price,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'payment_type' => 1,
                    ]);


                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                $this->adminDashboardCounterEvent();
                $this->setMessage('Delivery Payment Insert Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryPaymentList');
            } else {
                $this->setMessage('Delivery Payment Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Delivery Payment Insert Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function deliveryPaymentGenerateEdit(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {

        $parcelDeliveryPayment->load('branch', 'parcel_delivery_payment_details');

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        foreach ($parcelDeliveryPayment->parcel_delivery_payment_details as $parcel_delivery_payment_detail) {
            $cart_id = $parcel_delivery_payment_detail->parcel->id;
            \Cart::session($branch_id)->add([
                'id' => $cart_id,
                'name' => $parcel_delivery_payment_detail->parcel->merchant->name,
                'price' => $parcel_delivery_payment_detail->parcel->customer_collect_amount,
                'quantity' => 1,
                'target' => 'subtotal',
                'attributes' => [
                    'parcel_invoice' => $parcel_delivery_payment_detail->parcel->parcel_invoice,
                    'customer_name' => $parcel_delivery_payment_detail->parcel->customer_name,
                    'customer_address' => $parcel_delivery_payment_detail->parcel->customer_address,
                    'customer_contact_number' => $parcel_delivery_payment_detail->parcel->customer_contact_number,
                    'customer_collect_amount' => $parcel_delivery_payment_detail->parcel->customer_collect_amount,
                    'merchant_name' => $parcel_delivery_payment_detail->parcel->merchant->name,
                    'merchant_contact_number' => $parcel_delivery_payment_detail->parcel->merchant->contact_number,
                    'option' => [],
                ],
                'associatedModel' => $parcel_delivery_payment_detail->parcel,
            ]);
        }

        $data = [];
        $cart = \Cart::session($branch_id)->getContent();
        $data['cart'] = $cart->sortBy('id');
        $data['totalItem'] = \Cart::session($branch_id)->getTotalQuantity();
        $data['getTotal'] = \Cart::session($branch_id)->getTotal();
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryPaymentGenerate';
        $data['page_title'] = 'Delivery Payment Edit';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcelDeliveryPayment'] = $parcelDeliveryPayment;
        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
        }])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereRaw('delivery_branch_id = ? and  delivery_type in (1,2) and status >= 25  and (payment_type is null OR payment_type = 3)', [$branch_id])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
            ->get();

        return view('branch.parcel.deliveryPayment.deliveryPaymentGenerateEdit', $data);
    }


    public function confirmDeliveryPaymentGenerateEdit(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {

        $validator = Validator::make($request->all(), [
            'total_payment_parcel' => 'required',
            'total_payment_amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {

            $data = [
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_payment_parcel' => $request->input('total_payment_parcel'),
                'total_payment_amount' => $request->input('total_payment_amount'),
                'note' => $request->input('note'),
                'status' => 1,
            ];
            $check = ParcelDeliveryPayment::where('id', $parcelDeliveryPayment->id)->update($data);

            if ($check) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                $parcelDeliveryPaymentDetails = ParcelDeliveryPaymentDetail::where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->get();

                foreach ($parcelDeliveryPaymentDetails as $parcelDeliveryPaymentDetail) {
                    Parcel::where('id', $parcelDeliveryPaymentDetail->parcel_id)->update([
                        'payment_type' => null,
                    ]);
                }

                ParcelDeliveryPaymentDetail::where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->delete();


                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    ParcelDeliveryPaymentDetail::create([
                        'parcel_delivery_payment_id' => $parcelDeliveryPayment->id,
                        'parcel_id' => $parcel_id,
                        'amount' => $item->price,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'payment_type' => 1,
                    ]);

                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Delivery Payment Update Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryPaymentList');
            } else {
                $this->setMessage('Delivery Payment Update Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Delivery Payment Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function startDeliveryRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $branch_id = auth()->guard('branch')->user()->branch->id;
                $branch_user_id = auth()->guard('branch')->user()->id;

                $check = RiderRun::where('id', $request->rider_run_id)->update([
                    'start_date_time' => date('Y-m-d H:i:s'),
                    'status' => 2,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();
                    foreach ($riderRunDetails as $riderRunDetail) {
                        $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        $parcel->update([
                            'status' => 17,
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'parcel_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $riderRunDetail->parcel_id,
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 17,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 2,
                        ]);

                        $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();

                        $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                        // $merchant_user->notify(new MerchantParcelNotification($parcel));

                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Start Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function cancelDeliveryRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $branch_id = auth()->guard('branch')->user()->branch->id;
                $branch_user_id = auth()->guard('branch')->user()->id;


                $check = RiderRun::where('id', $request->rider_run_id)->update([
                    'cancel_date_time' => date('Y-m-d H:i:s'),
                    'status' => 3,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();
                    foreach ($riderRunDetails as $riderRunDetail) {
                        $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        $parcel->update([
                            'status' => 18,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $riderRunDetail->parcel_id,
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 18,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 3,
                        ]);

                        $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();

//                        $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
//                        $merchant_user->notify(new MerchantParcelNotification($parcel));

                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Cancel Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function viewDeliveryRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.deliveryParcel.viewDeliveryRiderRun', compact('riderRun'));
    }


    public function deliveryRiderRunReconciliation(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.deliveryParcel.deliveryRiderRunReconciliation', compact('riderRun'));
    }

    public function confirmDeliveryRiderRunReconciliation(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'run_note' => 'sometimes',
                'rider_run_id' => 'required',
                'total_run_complete_parcel' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $riderRun = RiderRun::where([
                    'id' => $request->rider_run_id,
                    'run_type' => 2,
                    'status' => 2,
                ])
                    ->update([
                        'complete_date_time' => date('Y-m-d H:i:s'),
                        'total_run_complete_parcel' => $request->total_run_complete_parcel,
                        'note' => $request->run_note,
                        'status' => 4,
                    ]);
                if ($riderRun) {
                    $rider_run_status = $request->rider_run_status;
                    $rider_run_details_id = $request->rider_run_details_id;
                    $parcel_id = $request->parcel_id;
                    $complete_type = $request->complete_type;
                    $customer_collect_amount = $request->customer_collect_amount;
                    $reschedule_parcel_date = $request->reschedule_parcel_date;
                    $complete_note = $request->complete_note;

                    $count = count($rider_run_details_id);

                    for ($i = 0; $i < $count; $i++) {
                        RiderRunDetail::where('id', $rider_run_details_id[$i])->update([
                            'complete_note' => $complete_note[$i],
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'status' => $rider_run_status[$i],
                        ]);

                        $parcel_update_data = [
                            'status' => 20,
                            'parcel_note' => $complete_note[$i],
                            'parcel_date' => date('Y-m-d'),
                            'pickup_branch_date' => date('Y-m-d'),
                        ];

                        $parcel_log_create_data = [
                            'parcel_id' => $parcel_id[$i],
                            'pickup_branch_id' => auth()->guard('branch')->user()->id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 20,
                        ];

                        switch ($complete_type[$i]) {
                            case 21 :
                                $parcel_update_data['status'] = 25;
                                $parcel_update_data['customer_collect_amount'] = $customer_collect_amount[$i];
                                $parcel_update_data['delivery_type'] = 1;
                                $parcel_log_create_data['status'] = 25;
                                break;

                            case 22 :
                                $parcel_update_data['status'] = 25;
                                $parcel_update_data['customer_collect_amount'] = $customer_collect_amount[$i];
                                $parcel_update_data['delivery_type'] = 2;

                                $parcel_log_create_data['status'] = 25;
                                break;

                            case 23 :
                                $parcel_update_data['status'] = 25;
                                $parcel_update_data['reschedule_parcel_date'] = $reschedule_parcel_date[$i];
                                $parcel_update_data['delivery_type'] = 3;

                                $parcel_log_create_data['status'] = 25;
                                $parcel_log_create_data['reschedule_parcel_date'] = $reschedule_parcel_date[$i];
                                break;

                            case 24 :
                                $parcel_update_data['status'] = 25;
                                $parcel_update_data['delivery_type'] = 4;
                                $parcel_log_create_data['status'] = 25;
                                break;
                            default:

                                break;
                        }
                        Parcel::where('id', $parcel_id[$i])->update($parcel_update_data);
                        $parcel=Parcel::where('id', $parcel_id[$i])->first();
                        $parcel_log_create_data['delivery_type']=  $parcel->delivery_type;
                        ParcelLog::create($parcel_log_create_data);

                        $parcel = Parcel::where('id', $parcel_id[$i])->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Reconciliation Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);

    }

}

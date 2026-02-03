<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Notifications\MerchantParcelNotification;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchDeliveryPaymentController extends Controller {

    /** Branch Delivery Payment List */
    public function branchDeliveryPaymentList() {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'branchDeliveryPaymentList';
        $data['page_title'] = 'Branch Delivery Payment List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.deliveryPayment.branchDeliveryPaymentList', $data);
    }

    public function getBranchDeliveryPaymentList(Request $request) {

        $model = ParcelDeliveryPayment::with(['branch' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->where('branch_id', $request->input('branch_id'));
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
                    case 1:$status_name  = "Payment Request"; $class  = "success";break;
                    case 2:$status_name  = "Payment Accept"; $class  = "success";break;
                    case 3:$status_name  = "Payment Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Branch Delivery Payment">
                <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" parcel_delivery_payment_id="' . $data->id . '" title="Print Delivery Payment" >
                <i class="fa fa-print"></i> </button>';
                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success accept-branch-delivery-payment btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Accept Branch Delivery Payment">
                    <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm reject-branch-delivery-payment" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Reject Branch Delivery Payment">
                            <i class="far fa-window-close"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }

    /** Branch Delivery Payment Receive List */
    public function branchDeliveryReceivePaymentList() {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'receivePaymentList';
        $data['page_title'] = 'Branch Delivery Receive Payment List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.deliveryPayment.receivePaymentList', $data);
    }

    public function getBranchDeliveryReceivePaymentList(Request $request) {

        $model = ParcelDeliveryPayment::with(['branch' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                $query->where('status', 2);

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->where('branch_id', $request->input('branch_id'));
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('date_time', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('date_time', '<=', $request->input('to_date'));
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
                    case 1:$status_name  = "Payment Request"; $class  = "success";break;
                    case 2:$status_name  = "Payment Accept"; $class  = "success";break;
                    case 3:$status_name  = "Payment Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Branch Delivery Payment">
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success accept-branch-delivery-payment btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Accept Branch Delivery Payment">
                    <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm reject-branch-delivery-payment" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Reject Branch Delivery Payment">
                            <i class="far fa-window-close"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }


    public function viewBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.viewBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }
    public function printBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.printBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function acceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.acceptBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function confirmAcceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'total_payment_received_parcel' => 'required',
                'total_payment_received_amount' => 'required',
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                            'id' => $parcelDeliveryPayment->id,

                        ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'total_payment_received_parcel' => $request->total_payment_received_parcel,
                            'total_payment_received_amount' => $request->total_payment_received_amount,
                            'note'                          => $request->note,
                            'status'                        => 2,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {
                        $parcel_delivery_payment_detail_status = $request->parcel_delivery_payment_detail_status;
                        $parcel_delivery_payment_detail_id     = $request->parcel_delivery_payment_detail_id;
                        $parcel_id                             = $request->parcel_id;
                        $amount                                = $request->amount;
                        $detail_note                           = $request->detail_note;

                        $count = count($parcel_delivery_payment_detail_id);

                        for ($i = 0; $i < $count; $i++) {
                            ParcelDeliveryPaymentDetail::where('id', $parcel_delivery_payment_detail_id[$i])->update([
                                'note'              => $detail_note[$i],
                                'date_time'         => date('Y-m-d H:i:s'),
                                'admin_id'          => $admin_id,
                                'status'            => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type'             => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            // $parcel = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();
                            // $message = "Dear ".$parcel->merchant->name.", Flier Express just delivered/partial delivered/Returned your product Reff-". $parcel->parcel_invoice." .  Please Collect the amount from accounts.";
                            // $this->send_sms($parcel->customer_contact_number, $message);


                            $parcel     = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();

                            $delivery_type = "";
                            if($parcel->delivery_type == 1 || $parcel->delivery_type == 2){
                                $delivery_type = "Delivered";
                            }
                            if($parcel->delivery_type == 4){
                                $delivery_type = "Canceled";
                            }

//                            $message    = "Dear ".$parcel->merchant->name.", ";
//                            $message    .= "For  Parcel ID No ".$parcel->parcel_invoice."  is successfully ".$delivery_type.".";
//                            $message    .= "Please rate your experience with us in our google play store app link.";
//                            $this->send_sms($parcel->merchant->contact_number, $message);


                            $merchant_user = Merchant::find($parcel->merchant_id);
                            //$merchant_user->notify(new MerchantParcelNotification($parcel));

                            //$this->merchantDashboardCounterEvent($parcel->merchant_id);

                            //$this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();
                        //$this->adminDashboardCounterEvent();

                        $response = ['success' => 'Accept Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
                }
            }
        }
        return response()->json($response);

    }


    public function rejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user');
        $parcelDeliveryPaymentDetails = ParcelDeliveryPaymentDetail::with('parcel')->where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->get();
        return view('admin.account.deliveryPayment.rejectBranchDeliveryPayment', compact('parcelDeliveryPayment', 'parcelDeliveryPaymentDetails'));
    }

    public function confirmRejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                            'id' => $parcelDeliveryPayment->id,
                        ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'note'                          => $request->note,
                            'total_payment_received_parcel' => 0,
                            'total_payment_received_amount' => 0,
                            'status'                        => 3,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {

                        ParcelDeliveryPaymentDetail::where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->update([
                            'date_time'         => date('Y-m-d H:i:s'),
                            'status'            => 3,
                        ]);

                        $parcel_id                             = $request->parcel_id;

                        $count = count($parcel_id);

                        for ($i = 0; $i < $count; $i++) {
                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type'             => 3,
                                'updated_admin_id'         => $admin_id,
                            ]);

                            $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            $merchant_user = Merchant::find($parcel->merchant_id);
                           // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            //$this->merchantDashboardCounterEvent($parcel->merchant_id);

                            //$this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();

                        //$this->adminDashboardCounterEvent();

                        $response = ['success' => 'Reject Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
//                    $response = ['error' => $e->getMessage()];
                }
            }
        }
        return response()->json($response);

    }



    /** Delivery Payment statement */
    public function branchDeliveryPaymentStatement()
    {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'branchPaymentStatement';
        $data['page_title'] = 'Branch Delivery Payment Statement';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['parcel_payment_reports']   = [];
        $data['payment_total_amount']     = 0;
        $data['payment_total_pending_amount']   = 0;
        $data['payment_total_receive_amount']   = 0;

        $from_date  = Carbon::now()->subMonth()->format("Y-m-d");
        $to_date    = Carbon::now()->format("Y-m-d");

        $parcel_payment_data    = ParcelDeliveryPaymentDetail::whereBetween('created_at', [$from_date, $to_date])->get();

        //dd($parcel_payment_data);
        $data['date_array'] = array();
        $data['pinvoice_array'] = array();
        if(count($parcel_payment_data) > 0) {

            foreach ($parcel_payment_data as $payment_data) {
                $delivery_date = date("Y-m-d", strtotime($payment_data->created_at));
                $payment_invoice    = $payment_data->parcel_delivery_payment->payment_invoice;
                $data['date_array'][$delivery_date][]    = $payment_data->id;
                $data['pinvoice_array'][$payment_invoice][]    = $payment_data->parcel->parcel_invoice;
            }
        }

        $data['parcel_payment_data'] = $parcel_payment_data;


        return view('admin.account.deliveryPayment.deliveryParcelPaymentStatement', $data);
    }


    public function getBranchDeliveryPaymentStatement(Request $request)
    {

        $data               = [];
        $parcel_payment_data    = ParcelDeliveryPaymentDetail::with(['parcel', 'parcel_delivery_payment'])

            ->where(function($query) use ($request){

                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                $branch_id = $request->input('branch_id');
                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->whereHas('parcel_delivery_payment', function($query)  use ($branch_id) {
                            $query->where('branch_id', $branch_id);


                    });
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('created_at', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('created_at', '<=', $request->input('to_date'));
                }
            })->get();

        //dd($parcel_payment_data);

        $data['date_array'] = array();
        $data['pinvoice_array'] = array();
        if(count($parcel_payment_data) > 0) {

            foreach ($parcel_payment_data as $payment_data) {
                $delivery_date = date("Y-m-d", strtotime($payment_data->created_at));
                $payment_invoice    = $payment_data->parcel_delivery_payment->payment_invoice;
                $data['date_array'][$delivery_date][]    = $payment_data->id;
                $data['pinvoice_array'][$payment_invoice][]    = $payment_data->parcel->parcel_invoice;
            }
        }

        $data['parcel_payment_data'] = $parcel_payment_data;


        return view('admin.account.deliveryPayment.filterBranchDeliveryPaymentStatement', $data);
    }


}

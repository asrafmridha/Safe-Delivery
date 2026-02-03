<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\ParcelPaymentRequest;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\MerchantDeliveryPaymentExport;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryPaymentController extends Controller {


    public function deliveryPaymentList() {
        $data               = [];
        $data['main_menu']  = 'account';
        $data['child_menu'] = 'deliveryPaymentList';
        $data['page_title'] = 'Delivery Payment List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.account.deliveryPaymentList', $data);
    }

    public function getDeliveryPaymentList(Request $request) {
        $merchant_id = auth()->guard('merchant')->user()->id;

        // $model = ParcelMerchantDeliveryPayment::with([
        //     'admin' => function ($query) {
        //         $query->select('id', 'name');
        //     },
        // ])
        
        
        $model = ParcelMerchantDeliveryPayment::with(['parcel_merchant_delivery_payment_details.parcel',
        'merchant' => function ($query) {
            
             $query->select('id', 'name');
        },
        ])
        
        
        ->whereRaw('merchant_id = ?', [$merchant_id])
        ->where(function($query) use($request){
            $status      = $request->input('status');
            $from_date          = $request->input('from_date');
            $to_date            = $request->input('to_date');

            if ( ($request->has('status')  && !is_null($status))
                || ($request->has('from_date')  && !is_null($from_date))
                || ($request->has('to_date')  && !is_null($to_date))
            ) {

                if ($request->has('status') && ! is_null($status) && $status != 0) {
                        $query->whereRaw('status = ?', $request->input('status'));
                }
                if ($request->has('from_date') && ! is_null($from_date)) {
                    $query->whereDate('date_time', '>=', $from_date);
                }
                if ($request->has('to_date') && ! is_null($to_date)) {
                    $query->whereDate('date_time', '<=', $to_date);
                }
            }
            else{
                $query->where('status', '!=', 3);
            }
        })
        ->orderBy('id', 'desc');

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
                    case 2:$status_name  = "Paid"; $class  = "success";break;
                    case 3:$status_name  = "Payment Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_merchant_delivery_payment_id="' . $data->id . '" title="View Delivery Payment">
                <i class="fa fa-eye"></i> </button>';
                
                 $button .= '&nbsp; <a href="' . route('merchant.account.printMerchantDeliveryPayment', $data->id) . '" class="btn btn-success btn-sm" title="Print Merchant Delivery Payment" target="_blank">
                <i class="fas fa-print"></i> </a>';
                
                
                 $button .= '&nbsp; <a class="btn btn-primary btn-sm" href="'. route('merchant.account.exportMerchantDeliveryPayment', $data->id) .'" title="Export Delivery Payment" target="_blank">
                <i class="fas fa-file-excel"></i> </a>';

                if($data->status == 1){
                    $button .= '&nbsp; <button class="btn btn-success merchant-delivery-payment-accept btn-sm" data-toggle="modal" data-target="#viewModal" parcel_merchant_delivery_payment_id="' . $data->id . '" title="Accept  Delivery Payment">
                    <i class="fa fa-check"></i>  </button>';

//                    $button .= '&nbsp; <button class="btn btn-danger merchant-delivery-payment-reject btn-sm" data-toggle="modal" data-target="#viewModal" parcel_merchant_delivery_payment_id="' . $data->id . '" title="Accept  Delivery Payment">
//                    <i class="far fa-window-close"></i>  </button>';
                }
                return $button;
            })
            
            
             ->addColumn('total_collect_amount', function ($data) {
                $total_collect_amount=0;
                foreach($data->parcel_merchant_delivery_payment_details as $v_data){
                    $total_collect_amount+=$v_data->parcel->total_collect_amount;
                }
                return $total_collect_amount;
            })
            ->addColumn('customer_collect_amount', function ($data) {
                $customer_collect_amount=0;
                foreach($data->parcel_merchant_delivery_payment_details as $v_data){
                    $customer_collect_amount+=$v_data->parcel->customer_collect_amount;
                }
                return $customer_collect_amount;
            })
            ->addColumn('total_charge', function ($data) {
                $total_charge=0;
                foreach($data->parcel_merchant_delivery_payment_details as $v_data){
                    $total_charge+=$v_data->parcel->total_charge;
                }
                return number_format($total_charge, 2);
            })
            
            
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time', 'total_collect_amount','customer_collect_amount','total_charge'])
            ->make(true);
    }

    public function viewMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('merchant.account.viewMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }
    
    
    public function exportMerchantDeliveryPayment($id) {
        
        $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::where('id',$id)->first();
        // dd($parcelMerchantDeliveryPayment->merchant_payment_invoice);
        $fileName= 'parcel_delivery_payment_'.$parcelMerchantDeliveryPayment->merchant_payment_invoice.'_'.time().'.xlsx';
        return Excel::download(new MerchantDeliveryPaymentExport($id), $fileName);
        
        // $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        // return view('merchant.account.viewMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }

    public function merchantDeliveryPaymentAccept(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('merchant.account.merchantDeliveryPaymentAccept', compact('parcelMerchantDeliveryPayment'));
    }

    public function merchantDeliveryPaymentAcceptConfirm(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $response = ['error' => 'Error Found'];
        $merchant_id = auth()->guard('merchant')->user()->id;

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

                        //dd($parcelMerchantDeliveryPayment->id, $ParcelMerchantDeliveryPaymentDetails);

                        foreach($ParcelMerchantDeliveryPaymentDetails as $ParcelMerchantDeliveryPaymentDetail){
                            Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->update([
                                'payment_type' => 5
                            ]);

                            $parcel = Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->first();
                            $merchant_user = Merchant::find($merchant_id);
                            $merchant_user->notify(new MerchantParcelNotification($parcel));
                        }
                        if ($payment_request){
                            $payment_request->update([
                                'status'    => 5,
//                                'action_admin_id'   => auth('admin')->user()->id
                            ]);
                        }
                        ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id',$parcelMerchantDeliveryPayment->id)
                            ->update([
                                'status'      => 2,
                                'date_time'   => date('Y-m-d H:i:s'),
                            ]);

                        \DB::commit();


                        // $this->merchantDashboardCounterEvent($merchant_id);
                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Payment Accept Successfully'];
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
    
      public function printMerchantDeliveryPayment(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('admin.account.merchantDeliveryPayment.printMerchantDeliveryPayment', compact('parcelMerchantDeliveryPayment'));
    }


    public function merchantDeliveryPaymentReject(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $parcelMerchantDeliveryPayment->load('admin', 'merchant', 'parcel_merchant_delivery_payment_details');
        return view('merchant.account.merchantDeliveryPaymentReject', compact('parcelMerchantDeliveryPayment'));
    }


    public function merchantDeliveryPaymentRejectConfirm(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $response = ['error' => 'Error Found'];
        $merchant_id    = auth()->guard('merchant')->user()->id;

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
                        'status'      => 3,
                        'note'      => $request->note,
                        'action_date_time' => date('Y-m-d H:i:s'),
                    ];
                    $check = ParcelMerchantDeliveryPayment::where('id',$parcelMerchantDeliveryPayment->id)->update($data);

                    if ($check) {

                        $ParcelMerchantDeliveryPaymentDetails =  ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id', $parcelMerchantDeliveryPayment->id)->get();
                        foreach($ParcelMerchantDeliveryPaymentDetails as $ParcelMerchantDeliveryPaymentDetail){
                            Parcel::where('id', $ParcelMerchantDeliveryPaymentDetail->parcel_id)->update([
                                'payment_type' => 6
                            ]);
                        }

                        ParcelMerchantDeliveryPaymentDetail::where('parcel_merchant_delivery_payment_id',$parcelMerchantDeliveryPayment->id)
                            ->update([
                                'status'      => 3,
                                'date_time'   => date('Y-m-d H:i:s'),
                            ]);

                        \DB::commit();

                        // $this->merchantDashboardCounterEvent($merchant_id);
                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Payment Reject Successfully'];
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






}

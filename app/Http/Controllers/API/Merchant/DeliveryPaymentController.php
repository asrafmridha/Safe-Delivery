<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\ParcelPaymentRequest;
use App\Notifications\MerchantParcelNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryPaymentController extends Controller {

    public function getDeliveryPaymentList(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $parcelMerchantDeliveryPayments = ParcelMerchantDeliveryPayment::with([
            'admin:id,name',
        ])
            ->whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if (($request->has('status') && !is_null($status))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {

                    if ($request->has('status') && !is_null($status) && $status != 0) {
                        $query->whereRaw('status = ?', $request->input('status'));
                    }

                    if ($request->has('from_date') && !is_null($from_date)) {
                        $query->whereDate('date_time', '>=', $from_date);
                    }

                    if ($request->has('to_date') && !is_null($to_date)) {
                        $query->whereDate('date_time', '<=', $to_date);
                    }

                } else {
                    $query->where('status', '!=', 3);
                }

            })
            ->orderBy('id', 'desc')
            ->get();

        $delivery_payment_lists = [];

        foreach ($parcelMerchantDeliveryPayments as $parcelMerchantDeliveryPayment) {

            switch ($parcelMerchantDeliveryPayment->status) {
            case 1:$status = "Processing";
                break;
            case 2:$status = "Paid";
                break;
            case 3:$status = "Delivery Payment Cancel";
                break;
            default:$status = "";
                break;
            }

            $delivery_payment_lists[] = [
                'id'                            => $parcelMerchantDeliveryPayment->id,
                'merchant_payment_invoice'      => $parcelMerchantDeliveryPayment->merchant_payment_invoice,
                'admin_id'                      => $parcelMerchantDeliveryPayment->admin_id,
                'total_payment_parcel'          => $parcelMerchantDeliveryPayment->total_payment_parcel,
                'total_payment_received_parcel' => $parcelMerchantDeliveryPayment->total_payment_received_parcel,
                'total_payment_amount'          => number_format($parcelMerchantDeliveryPayment->total_payment_amount,2),
                'total_payment_received_amount' => number_format($parcelMerchantDeliveryPayment->total_payment_received_amount, 2),
                'note'                          => $parcelMerchantDeliveryPayment->note,
                'date_time'                     => $parcelMerchantDeliveryPayment->date_time,
                'status'                        => $status,
                'status_no'                     => $parcelMerchantDeliveryPayment->status,
                'admin_name'                    => $parcelMerchantDeliveryPayment->admin->name,
            ];
        }

        return response()->json([
            'success'                => 200,
            'message'                => "Accounts Merchant Payment Results",
            // 'delivery_payment_lists' => $parcelMerchantDeliveryPayments,
            'delivery_payment_lists' => $delivery_payment_lists,
        ], 200);
    }

    public function getDeliveryPayment(Request $request) {

        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error'   => $validator->errors(),
            ], 401);
        }

        $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::with([
            'admin:id,name',
            'parcel_merchant_delivery_payment_details',
        ])
            ->where('merchant_id', $merchant_id)
            ->where('id', $request->id)
            ->first();

        if ($parcelMerchantDeliveryPayment) {


            switch ($parcelMerchantDeliveryPayment->status) {
                case 1:$status = "Processing";
                    break;
                case 2:$status = "Paid";
                    break;
                case 3:$status = "Delivery Payment Cancel";
                    break;
                default:$status = "";
                    break;
            }

            $delivery_payment = [
                'id'                            => $parcelMerchantDeliveryPayment->id,
                'merchant_payment_invoice'      => $parcelMerchantDeliveryPayment->merchant_payment_invoice,
                'admin_id'                      => $parcelMerchantDeliveryPayment->admin_id,
                'total_payment_parcel'          => $parcelMerchantDeliveryPayment->total_payment_parcel,
                'total_payment_received_parcel' => $parcelMerchantDeliveryPayment->total_payment_received_parcel,
                'total_payment_amount'          => number_format($parcelMerchantDeliveryPayment->total_payment_amount,2),
                'total_payment_received_amount' => number_format($parcelMerchantDeliveryPayment->total_payment_received_amount,2),
                'note'                          => $parcelMerchantDeliveryPayment->note,
                'date_time'                     => $parcelMerchantDeliveryPayment->date_time,
                'status'                        => $status,
                'admin_id'                      => $parcelMerchantDeliveryPayment->admin_id,
                'admin_name'                    => $parcelMerchantDeliveryPayment->admin->name,
            ];

            $delivery_payment_details = [];
            foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $delivery_payment_detail) {

                switch ($delivery_payment_detail->status) {
                case 1:$status = "Processing";
                    break;
                case 2:$status = "Paid";
                    break;
                case 3:$status = "Delivery Payment Cancel";
                    break;
                default:$status = "";
                    break;
                }

                $delivery_payment_details[] = [
                    'id'                    => $delivery_payment_detail->id,
                    'parcel_id'             => $delivery_payment_detail->parcel_id,
                    'parcel_invoice'        => $delivery_payment_detail->parcel->parcel_invoice,
                    'customer_name'        => $delivery_payment_detail->parcel->customer_name,
                    'customer_contact_number'        => $delivery_payment_detail->parcel->customer_contact_number,
                    'merchant_order_id'     => $delivery_payment_detail->parcel->merchant_order_id,
                    'collected_amount'      => number_format($delivery_payment_detail->collected_amount, 2),
                    'cod_charge'            => number_format($delivery_payment_detail->cod_charge, 2),
                    'delivery_charge'       => number_format($delivery_payment_detail->delivery_charge, 2),
                    'weight_package_charge' => number_format($delivery_payment_detail->weight_package_charge, 2),
                    'return_charge'         => number_format($delivery_payment_detail->return_charge, 2),
                    'total_charge'          => (number_format($delivery_payment_detail->delivery_charge, 2)+number_format($delivery_payment_detail->weight_package_charge, 2)+number_format($delivery_payment_detail->return_charge, 2)+number_format($delivery_payment_detail->cod_charge, 2)),
                    'paid_amount'           => number_format($delivery_payment_detail->paid_amount, 2),
                    'note'                  => $delivery_payment_detail->note,
                    'date_time'             => $delivery_payment_detail->date_time,
                    'status'                => $status,
                ];
            }

            return response()->json([
                'success'          => 200,
                'message'          => "Delivery Payment found",
                // 'delivery_payment' => $delivery_payment,
                'delivery_payment' => $delivery_payment,
                'delivery_payment_details' => $delivery_payment_details,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Delivery Payment Not found",
            'error'   => "Delivery Payment Not found",
        ], 401);

    }

    public function merchantDeliveryPaymentAcceptConfirm(Request $request, ParcelMerchantDeliveryPayment $parcelMerchantDeliveryPayment) {
        $response = ['error' => 'Error Found'];
        $merchant_id = auth()->guard('merchant_api')->user()->id;

            $validator = Validator::make($request->all(), [
                'note' => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 401,
                    'message' => "Validation Error.",
                    'error'   => $validator->errors(),
                ], 401);
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
                        return response()->json([
                            'success' => true,
                            'message' => 'Payment Accept Successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => 401,
                            'message' => 'Database Error Found',
                        ], 401);
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    return response()->json([
                        'success' => 401,
                        'message' => 'Database Error Found',
                        'error'   => $e->getMessage(),
                    ], 401);
                }
            }
    }

}

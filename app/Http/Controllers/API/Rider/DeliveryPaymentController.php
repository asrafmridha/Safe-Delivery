<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\ParcelMerchantDeliveryPayment;
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
            case 1:$status = "Delivery Payment Send";
                break;
            case 2:$status = "Delivery Payment Accept";
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
                'total_payment_amount'          => $parcelMerchantDeliveryPayment->total_payment_amount,
                'total_payment_received_amount' => $parcelMerchantDeliveryPayment->total_payment_received_amount,
                'note'                          => $parcelMerchantDeliveryPayment->note,
                'date_time'                     => $parcelMerchantDeliveryPayment->date_time,
                'status'                        => $status,
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
            case 1:$status = "Delivery Payment Send";
                break;
            case 2:$status = "Delivery Payment Accept";
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
                'total_payment_amount'          => $parcelMerchantDeliveryPayment->total_payment_amount,
                'total_payment_received_amount' => $parcelMerchantDeliveryPayment->total_payment_received_amount,
                'note'                          => $parcelMerchantDeliveryPayment->note,
                'date_time'                     => $parcelMerchantDeliveryPayment->date_time,
                'status'                        => $status,
                'admin_id'                      => $parcelMerchantDeliveryPayment->admin_id,
                'admin_name'                    => $parcelMerchantDeliveryPayment->admin->name,
            ];

            $delivery_payment_details = [];
            foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $delivery_payment_detail) {

                switch ($delivery_payment_detail->status) {
                case 1:$status = "Delivery Payment Send";
                    break;
                case 2:$status = "Delivery Payment Accept";
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
                    'merchant_order_id'     => $delivery_payment_detail->parcel->merchant_order_id,
                    'collected_amount'      => $delivery_payment_detail->collected_amount,
                    'cod_charge'            => $delivery_payment_detail->cod_charge,
                    'delivery_charge'       => $delivery_payment_detail->delivery_charge,
                    'weight_package_charge' => $delivery_payment_detail->weight_package_charge,
                    'return_charge'         => $delivery_payment_detail->return_charge,
                    'paid_amount'           => $delivery_payment_detail->paid_amount,
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

}

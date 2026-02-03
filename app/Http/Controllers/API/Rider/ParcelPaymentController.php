<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\MerchantBulkParcelImport;
use App\Models\ParcelMerchantDeliveryPayment;

class ParcelPaymentController extends Controller {




    public function getParcelPaymentList(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $ParcelPayments = Parcel::whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function($query) use($request){
                $delivery_parcel_type          = $request->input('delivery_parcel_type');
                $parcel_invoice         = $request->input('parcel_invoice');
                $merchant_order_id      = $request->input('merchant_order_id');
                $customer_contact_number= $request->input('customer_contact_number');
                $from_date              = $request->input('from_date');
                $to_date                = $request->input('to_date');

                if ( ($request->has('delivery_parcel_type')  && !is_null($delivery_parcel_type))
                    || ($request->has('parcel_invoice')  && !is_null($parcel_invoice))
                    || ($request->has('customer_contact_number')  && !is_null($customer_contact_number))
                    || ($request->has('merchant_order_id')  && !is_null($merchant_order_id))
                    || ($request->has('from_date')  && !is_null($from_date))
                    || ($request->has('to_date')  && !is_null($to_date))
                ) {
                    if((!is_null($parcel_invoice) && !is_null($parcel_invoice))
                    || (!is_null($merchant_order_id) && !is_null($merchant_order_id))
                    || (!is_null($customer_contact_number) && !is_null($customer_contact_number))
                    ){
                        if(!is_null($parcel_invoice) && !is_null($parcel_invoice)){
                            $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                        }
                        elseif(!is_null($merchant_order_id) && !is_null($merchant_order_id)){
                            $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                        }
                        elseif(!is_null($customer_contact_number) && !is_null($customer_contact_number)){
                            $query->where('customer_contact_number', 'like', "%$customer_contact_number");
                        }
                    }
                    else{
                        if ($request->has('delivery_parcel_type') && ! is_null($delivery_parcel_type) && $delivery_parcel_type != 0) {
                            if($delivery_parcel_type == 1){
                                $query->whereRaw('delivery_type = 1');
                            }
                            elseif($delivery_parcel_type == 2){
                                $query->whereRaw('delivery_type = 2');
                            }
                            elseif($delivery_parcel_type == 3){
                                $query->whereRaw('delivery_type = 4');
                            }
                            elseif($delivery_parcel_type == 4){
                                $query->whereRaw('status < 11');
                            }
                            elseif($delivery_parcel_type == 5){
                                $query->whereRaw('status >= 11 and status < 25');
                            }
                            elseif($delivery_parcel_type == 6){
                                $query->whereRaw('delivery_type = 4 and status > 25');
                            }
                        }
                        if ($request->has('from_date') && ! is_null($from_date)) {
                            $query->whereDate('date', '>=', $from_date);
                        }
                        if ($request->has('to_date') && ! is_null($to_date)) {
                            $query->whereDate('date', '<=', $to_date);
                        }
                    }
                }
                $query->where('status', '!=', 3);
            })
            ->orderBy('id', 'desc')
            ->select(
                'id as parcel_id', 'parcel_invoice',
                'total_collect_amount', 'cod_percent',
                'cod_charge', 'weight_package_charge',
                'delivery_charge', 'return_charge',
                'customer_collect_amount', 'total_charge',
                'merchant_paid_amount'
            )
            ->get();

            return response()->json([
                'success'                => 200,
                'message'                => "Accounts Merchant Payment Results",
                'delivery_payment_lists' => $ParcelPayments,
            ], 200);
    }


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


}

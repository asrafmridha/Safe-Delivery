<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelPaymentRequest;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParcelPaymentRequestController extends Controller {

    public function parcelPaymentRequest() {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $parcels    = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1,2,2,4,6,0])
            ->get();

        $total_parcel_collect_amount = 0;
        $total_parcel_charge_amount = 0;
        $parcel_ids = [];
        if($parcels) {
            foreach ($parcels as $parcel) {

                $total_parcel_collect_amount += $parcel->customer_collect_amount;
                $total_parcel_charge_amount  += $parcel->total_charge;
                $parcel_ids[]   = $parcel->id;
            }
        }

        $data   = array();
        $data['request_amount'] = $total_parcel_collect_amount - $total_parcel_charge_amount;
        $data['parcel_ids']     = json_encode($parcel_ids);

        return response()->json([
            'success' => 200,
            'message' => "Parcel and request amount data",
            'parcels' => $data,
        ], 200);
    }

    public function confirmPaymentRequestGenerate(Request $request) {

        $validator = Validator::make($request->all(), [
            'date'                  => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
            'note'                  => 'sometimes',
            'request_amount'        => 'required|gt:0',
            'parcel_ids'            => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $merchant = auth()->guard('merchant_api')->user();
//        $check = ParcelPaymentRequest::where(['merchant_id'=>$merchant->id,'status'=>1])->first();
        $check = ParcelPaymentRequest::whereRaw("merchant_id = '{$merchant->id}' AND status < 5 AND status NOT IN (3)")->first();
        if($check == null){
            \DB::beginTransaction();
            try {
                $data = [
                    'payment_request_invoice'   => $this->returnUniquePaymentRequestInvoice(),
                    'merchant_id'               => $merchant->id,
                    'request_amount'            => $request->input('request_amount'),
                    'parcel_ids'                => $request->input('parcel_ids'),
                    'date'                      => $request->input('date').' '.date('H:i:s'),
                    'note'                      => $request->input('note'),
                ];

                if($request->input('request_payment_type') == 2) {

                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['bank_name']              = $request->input('bank_name');
                    $data['bank_account_no']        = $request->input('bank_account_no');
                    $data['bank_account_name']      = $request->input('bank_account_name');
                    $data['routing_no']             = $request->input('routing_no');
                }
                elseif($request->input('request_payment_type') == 3) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['bkash_number']              = $request->input('bkash_number');
                }
                elseif($request->input('request_payment_type') == 4) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['rocket_number']              = $request->input('rocket_number');
                }
                elseif($request->input('request_payment_type') == 5) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['nagad_number']              = $request->input('nagad_number');
                }
                else{
                    $data['request_payment_type']   = 1;
                }
//dd(json_decode($request->input('parcel_ids')));
                $parcelPaymentRequest = ParcelPaymentRequest::create($data);

                if ($parcelPaymentRequest) {

                    $parcel_update = Parcel::whereIn('id', json_decode($request->input('parcel_ids')))->update(['payment_request_status' => 1]);

                    \DB::commit();
                    return response()->json([
                        'success'   => 200,
                        'message'   => "Parcel Payment Request Send Successfully",
                    ], 200);

                } else {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Payment Request Send Failed",
                        'error'   => "Parcel Payment Request Send Failed",
                    ], 401);
                }

            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json([
                    'success' => 401,
                    'message' => "Database Error",
                    'error'   => $e->getMessage(),
                ], 401);
            }
        }
        else{
            return response()->json([
                'success' => 401,
                'message' => "Already you have a pending parcel payment request.",
                'error'   => "Already you have a pending parcel payment request.",
            ], 401);
        }
    }


    public function getParcelPaymentRequestList(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;
        $payment_requests       = ParcelPaymentRequest::whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
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
            ->orderBy('id', 'desc')->get();


        $payment_request_data    = [];

        if(count($payment_requests) > 0) {
            foreach ($payment_requests as $payment_request)
            {
                $status = "";
                switch ($payment_request->status) {
                    case 1:$status = "Requested";
                        break;
                    case 2:$status = "Accepted";
                        break;
                    case 3:$status = "Rejected";
                        break;
                    case 4:$status = "Processing";
                        break;
                    case 5:$status = "Paid";
                        break;
                    default:$status = "";break;
                }
                $payment_request_data[] = [
                    'id'        => $payment_request->id,
                    'payment_request_invoice'   => $payment_request->payment_request_invoice,
                    'date'      => $payment_request->date,
                    'note'      => $payment_request->note,
                    'status'    => $status
                ];
            }
        }

        return response()->json([
            'success' => 200,
            'message' => "Payment Request List",
            'payment_request_data' => $payment_request_data,
        ], 200);
    }

    public function viewParcelPaymentRequest(Request $request) {

        $validator = Validator::make($request->all(), [
            'payment_request_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $payment_request       = ParcelPaymentRequest::find($request->input('payment_request_id'));

        $payment_request_data    = [];

        if($payment_request) {
            $status = "";
            switch ($payment_request->status) {
                case 1:$status = "Requested";
                    break;
                case 2:$status = "Accepted";
                    break;
                case 3:$status = "Rejected";
                    break;
                case 4:$status = "Processing";
                    break;
                case 5:$status = "Paid";
                    break;
                default:$status = "";break;
            }
            $payment_request_data = [
                'id'                    => $payment_request->id,
                'payment_request_invoice'   => $payment_request->payment_request_invoice,
                'date'                  => $payment_request->date,
                'request_amount'        => $payment_request->request_amount,
                'note'                  => $payment_request->note,
                'status'                => $status
            ];
        }
        return response()->json([
            'success' => 200,
            'message' => "Payment Request Data",
            'payment_request_data' => $payment_request_data,
        ], 200);

    }

}

<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\MerchantBulkParcelImport;
use App\Models\Upazila;
use App\Models\Area;
use App\Models\ParcelPickupRequest;
use App\Models\WeightPackage;

class ParcelPickupRequestController extends Controller {

    public function confirmPickupRequestGenerate(Request $request) {

        // return 'ok';

        $validator = Validator::make($request->all(), [
            'request_type'      => 'required',
            'date'              => 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d'),
            'total_parcel'      => 'required|min:1',
            'note'              => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $merchant    = auth()->guard('merchant_api')->user();

        $check = ParcelPickupRequest::where(['merchant_id'=> $merchant->id,'status'=>1])->first();
        if($check == null){
            \DB::beginTransaction();
            try {
                $data = [
                    'pickup_request_invoice'        => $this->returnUniquePickupRequestInvoice(),
                    'merchant_id'                   => $merchant->id,
                    'branch_id'                     => $merchant->branch_id,
                    'request_type'                  => $request->input('request_type'),
                    'date'                          => $request->input('date'),
                    'total_parcel'                  => $request->input('total_parcel'),
                    'note'                          => $request->input('note'),
                ];
                $parcelPickupRequest = ParcelPickupRequest::create($data);
                if ($parcelPickupRequest) {

                    \DB::commit();
                    return response()->json([
                        'success'   => 200,
                        'message'   => "Parcel Pickup Request Send Successfully",
                    ], 200);

                } else {
                    return response()->json([
                        'success' => 401,
                        'message' => "Parcel Pickup Request Send Failed",
                        'error'   => "Parcel Pickup Request Send Failed",
                    ], 401);
                }
            }
            catch (\Exception $e){
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
                'message' => "Already have a pending parcel pickup request.",
                'error'   => "Already have a pending parcel pickup request.",
            ], 401);
        }
    }


    public function getParcelPickupRequestList(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;
        $parcel_pickup_requests = ParcelPickupRequest::whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function($query) use($request){
                $status          = $request->input('status');
                $from_date       = $request->input('from_date');
                $to_date         = $request->input('to_date');

                if ( ($request->has('status')  && !is_null($status))
                    || ($request->has('from_date')  && !is_null($from_date))
                    || ($request->has('to_date')  && !is_null($to_date))
                ) {
                    if ($request->has('status') && ! is_null($status)) {
                        $query->where('status', $status);
                    }

                    if ($request->has('from_date') && ! is_null($from_date)) {
                        $query->whereDate('date', '>=', $from_date);
                    }
                    if ($request->has('to_date') && ! is_null($to_date)) {
                        $query->whereDate('date', '<=', $to_date);
                    }
                }

//                else{
//                    $query->whereDate('date', '=', date('Y-m-d'));
//                }
            })
            ->orderBy('id', 'desc')->get();

        $pickup_request_data    = [];

        if(count($parcel_pickup_requests) > 0) {
            foreach ($parcel_pickup_requests as $pickup_request)
            {
                $status = "";
                switch ($pickup_request->status) {
                    case 1:$status = "Requested";
                        break;
                    case 2:$status = "Accepted";
                        break;
                    case 3:$status = "Rejected";
                        break;
                    case 4:$status = "Rider Assigned";
                        break;
                    case 5:$status = "Request Complete";
                        break;
                    default:$status = "";break;
                }
                $pickup_request_data[] = [
                    'id'                       => $pickup_request->id,
                    'pickup_request_invoice'   => $pickup_request->pickup_request_invoice,
                    'date'                     => $pickup_request->date,
                    'total_parcel'             => $pickup_request->total_parcel,
                    'note'                     => $pickup_request->note,
                    'status'                   => $status
                ];
                // dd($pickup_request_data);
            }
        }

        return response()->json([
            'success' => 200,
            'message' => "Pickup Parcel Request List",
            'pickup_request_data' => $pickup_request_data,
        ], 200);

    }


    public function viewParcelPickupRequest(Request $request) {

        $validator = Validator::make($request->all(), [
            'pickup_request_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $pickup_request       = ParcelPickupRequest::with(['riders'])->find($request->input('pickup_request_id'));

        $pickup_request_data    = [];

        if($pickup_request) {
            $status = "";
            switch ($pickup_request->status) {
                case 1:$status = "Requested";
                    break;
                case 2:$status = "Accepted";
                    break;
                case 3:$status = "Rejected";
                    break;
                case 4:$status = "Rider Assigned";
                    break;
                case 5:$status = "Request Complete";
                    break;
                default:$status = "";break;
            }

            $rider_name = ($pickup_request->riders) ? $pickup_request->riders->name : "Rider Name";
            $rider_contact_number = ($pickup_request->riders) ? $pickup_request->riders->contact_number : "Rider Phone";
            $pickup_request_data = [
                'id'                        => $pickup_request->id,
                'pickup_request_invoice'    => $pickup_request->pickup_request_invoice,
                'date'                      => $pickup_request->date,
                'total_parcel'              => $pickup_request->total_parcel,
                'total_complete_parcel'     => $pickup_request->total_complete_parcel,
                'rider_name'                => $rider_name,
                'rider_contact_number'      => $rider_contact_number,
                'note'                      => $pickup_request->note,
                'status'                    => $status
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Pickup Request Data",
            'pickup_request_data' => $pickup_request_data,
        ], 200);

    }


}

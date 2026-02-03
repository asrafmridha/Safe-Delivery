<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentParcelController extends Controller
{

    public function collectionParcelList()
    {
        $data = [];
        $rider_id = auth()->guard('rider_api')->user()->id;
        $parcels = Parcel::with(['merchant:id,company_name,address,contact_number'])
            ->whereRaw('(delivery_rider_id = ? and delivery_type in (1,2) and status in (21,22,25) )', [$rider_id])
            ->whereDate('delivery_rider_date', '>=', Carbon::now()->subDays(30))
            ->orderBy('id', 'desc')
            ->get();

        $new_parcels = [];

        foreach ($parcels as $parcel) {
            switch ($parcel->status) {
                case 1 :
                    $parcel_status = "Parcel Send Pick Request";
                    break;
                case 2 :
                    $parcel_status = "Parcel Hold";
                    break;
                case 3 :
                    $parcel_status = "Parcel Cancel";
                    break;
                case 4 :
                    $parcel_status = "Parcel Reschedule";
                    break;
                case 5 :
                    $parcel_status = "Pickup Run Start";
                    break;
                case 6 :
                    $parcel_status = "Pickup Run Create";
                    break;
                case 7 :
                    $parcel_status = "Pickup Run Cancel";
                    break;
                case 8 :
                    $parcel_status = "Pickup Run Accept Rider";
                    break;
                case 9 :
                    $parcel_status = "Pickup Run Cancel Rider";
                    break;
                case 10 :
                    $parcel_status = "Pickup Run Complete Rider";
                    break;
                case 11 :
                    $parcel_status = "Pickup Complete";
                    break;
                case 12 :
                    $parcel_status = "Assign Delivery Branch";
                    break;
                case 13 :
                    $parcel_status = "Assign Delivery Branch Cancel";
                    break;
                case 14 :
                    $parcel_status = "Assign Delivery Branch Received";
                    break;
                case 15 :
                    $parcel_status = "Assign Delivery Branch Reject";
                    break;
                case 16 :
                    $parcel_status = "Delivery Run Create";
                    break;
                case 17 :
                    $parcel_status = "Delivery Run Start";
                    break;
                case 18 :
                    $parcel_status = "Delivery Run Cancel";
                    break;
                case 19 :
                    $parcel_status = "Delivery Rider Accept";
                    break;
                case 20 :
                    $parcel_status = "Delivery Rider Reject";
                    break;
                case 21 :
                    $parcel_status = "Rider Delivered";
                    break;
                case 22 :
                    $parcel_status = "Delivery Rider Partial Delivery";
                    break;
                case 23 :
                    $parcel_status = "Delivery Rider Reschedule";
                    break;
                case 24 :
                    $parcel_status = "Delivery Rider Return";
                    break;
                case 25 :
                    $parcel_status = "Delivery  Complete";
                    break;
                case 26 :
                    $parcel_status = "Return Branch Assign";
                    break;
                case 27 :
                    $parcel_status = "Return Branch Assign Cancel";
                    break;
                case 28 :
                    $parcel_status = "Return Branch Assign Received";
                    break;
                case 29 :
                    $parcel_status = "Return Branch Assign Reject";
                    break;
                case 30 :
                    $parcel_status = "Return Branch   Run Create";
                    break;
                case 31 :
                    $parcel_status = "Return Branch  Run Start";
                    break;
                case 32 :
                    $parcel_status = "Return Branch  Run Cancel";
                    break;
                case 33 :
                    $parcel_status = "Return Rider Accept";
                    break;
                case 34 :
                    $parcel_status = "Return Rider Reject";
                    break;
                case 35 :
                    $parcel_status = "Return Rider Complete";
                    break;
                case 36 :
                    $parcel_status = "Return Branch  Run Complete";
                    break;
                default :
                    break;
            }

            $new_parcels[] = [
                'parcel_id' => $parcel->id,
                'parcel_invoice' => $parcel->parcel_invoice,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                // 'total_collect_amount'        => $parcel->total_collect_amount,
                'collect_amount' => $parcel->customer_collect_amount,
                'amount_to_be_collect' => $parcel->customer_collect_amount,
                'district_name' => $parcel->district->name,
                'upazila_name' => $parcel->upazila->name,
                'area_name' => $parcel->area->name,
                'weight_package_name' => $parcel->weight_package->name,
                'merchant_id' => $parcel->merchant->id,
                'merchant_name' => $parcel->merchant->company_name,
                'merchant_address' => $parcel->merchant->address,
                'merchant_contact_number' => $parcel->merchant->contact_number,
                'parcel_status' => $parcel_status,
                'delivery_rider_date' => $parcel->delivery_rider_date,
                'status' => $parcel->status,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Delivery Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }

    public function paidAmountParcelList()
    {

        $rider_id = auth()->guard('rider_api')->user()->id;
        $parcels = Parcel::with(['merchant:id,company_name,address,contact_number'])
            ->whereRaw('(delivery_rider_id = ? and delivery_type in (1,2) and status >= ? )', [$rider_id, 25])
            ->orderBy('id', 'desc')
            ->get();

        $new_parcels = [];

        foreach ($parcels as $parcel) {
            switch ($parcel->status) {
                case 1 :
                    $parcel_status = "Parcel Send Pick Request";
                    break;
                case 2 :
                    $parcel_status = "Parcel Hold";
                    break;
                case 3 :
                    $parcel_status = "Parcel Cancel";
                    break;
                case 4 :
                    $parcel_status = "Parcel Reschedule";
                    break;
                case 5 :
                    $parcel_status = "Pickup Run Start";
                    break;
                case 6 :
                    $parcel_status = "Pickup Run Create";
                    break;
                case 7 :
                    $parcel_status = "Pickup Run Cancel";
                    break;
                case 8 :
                    $parcel_status = "Pickup Run Accept Rider";
                    break;
                case 9 :
                    $parcel_status = "Pickup Run Cancel Rider";
                    break;
                case 10 :
                    $parcel_status = "Pickup Run Complete Rider";
                    break;
                case 11 :
                    $parcel_status = "Pickup Complete";
                    break;
                case 12 :
                    $parcel_status = "Assign Delivery Branch";
                    break;
                case 13 :
                    $parcel_status = "Assign Delivery Branch Cancel";
                    break;
                case 14 :
                    $parcel_status = "Assign Delivery Branch Received";
                    break;
                case 15 :
                    $parcel_status = "Assign Delivery Branch Reject";
                    break;
                case 16 :
                    $parcel_status = "Delivery Run Create";
                    break;
                case 17 :
                    $parcel_status = "Delivery Run Start";
                    break;
                case 18 :
                    $parcel_status = "Delivery Run Cancel";
                    break;
                case 19 :
                    $parcel_status = "Delivery Run Rider Accept";
                    break;
                case 20 :
                    $parcel_status = "Delivery Run Rider Reject";
                    break;
                case 21 :
                    $parcel_status = "Delivery Rider Delivery";
                    break;
                case 22 :
                    $parcel_status = "Delivery Rider Partial Delivery";
                    break;
                case 23 :
                    $parcel_status = "Delivery Rider Reschedule";
                    break;
                case 24 :
                    $parcel_status = "Delivery Rider Return";
                    break;
                case 25 :
                    $parcel_status = "Delivery  Complete";
                    break;
                case 26 :
                    $parcel_status = "Return Branch Assign";
                    break;
                case 27 :
                    $parcel_status = "Return Branch Assign Cancel";
                    break;
                case 28 :
                    $parcel_status = "Return Branch Assign Received";
                    break;
                case 29 :
                    $parcel_status = "Return Branch Assign Reject";
                    break;
                case 30 :
                    $parcel_status = "Return Branch   Run Create";
                    break;
                case 31 :
                    $parcel_status = "Return Branch  Run Start";
                    break;
                case 32 :
                    $parcel_status = "Return Branch  Run Cancel";
                    break;
                case 33 :
                    $parcel_status = "Return Rider Accept";
                    break;
                case 34 :
                    $parcel_status = "Return Rider Reject";
                    break;
                case 35 :
                    $parcel_status = "Return Rider Complete";
                    break;
                case 36 :
                    $parcel_status = "Return Branch  Run Complete";
                    break;
                default :
                    break;
            }

            $new_parcels[] = [
                'parcel_id' => $parcel->id,
                'parcel_invoice' => $parcel->parcel_invoice,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                // 'total_collect_amount'        => $parcel->total_collect_amount,
                'amount_to_be_collect' => $parcel->total_collect_amount,
                'collect_amount' => $parcel->customer_collect_amount,
                'district_name' => $parcel->district->name,
                'upazila_name' => $parcel->upazila->name,
                'area_name' => $parcel->area->name,
                'weight_package_name' => $parcel->weight_package->name,
                'merchant_id' => $parcel->merchant->id,
                'merchant_name' => $parcel->merchant->company_name,
                'merchant_address' => $parcel->merchant->address,
                'merchant_contact_number' => $parcel->merchant->contact_number,
                'delivery_rider_date' => $parcel->delivery_rider_date,
                'parcel_status' => $parcel_status,
                'status' => $parcel->status,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Delivery Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }


}

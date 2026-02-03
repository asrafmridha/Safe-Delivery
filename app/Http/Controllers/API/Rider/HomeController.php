<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use Illuminate\Http\Request;
use App\Models\Rider;
use Carbon\Carbon;
use App\Models\ParcelPickupRequest;



class HomeController extends Controller {

    public function dashboard(Request $request) {
        $rider_id   = auth()->guard('rider_api')->user()->id;

        $data       = [];

        /** E-courier */

        /** Today Pickup and Delivery Parcels */
        $data['todayPickupParcel']      = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (5,6,8)', [$rider_id, date("Y-m-d")])->count();
        $data['todayPickupComplete']    = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (10)', [$rider_id, date("Y-m-d")])->count();
        $data['todayPickupPending']     = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (8)', [$rider_id, date("Y-m-d")])->count();
        $data['todayPickupCancel']      = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (9)', [$rider_id, date("Y-m-d")])->count();

        $data['todayDeliveryParcels']   = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (16,17,19)', [$rider_id, date("Y-m-d")])->count();
        $data['todayDeliveryComplete']  = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (21, 22)', [$rider_id, date("Y-m-d")])->count();
        $data['todayDeliveryPending']   = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (19)', [$rider_id, date("Y-m-d")])->count();
        $data['todayDeliveryCancel']    = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (20)', [$rider_id, date("Y-m-d")])->count();

        /** Total Pickup and Delivery Parcels */
        $data['totalPickupParcel']      = Parcel::whereRaw('pickup_rider_id = ? and status in (5,6,8)', [$rider_id])->count();
        $data['totalPickupComplete']    = Parcel::whereRaw('pickup_rider_id = ? and status in (10)', [$rider_id])->count();
        $data['totalPickupPending']     = Parcel::whereRaw('pickup_rider_id = ? and status in (8)', [$rider_id])->count();
        $data['totalPickupCancel']      = Parcel::whereRaw('pickup_rider_id = ? and status in (9)', [$rider_id])->count();

        $data['totalDeliveryParcels']   = Parcel::whereRaw('delivery_rider_id = ? ', [$rider_id])->count();
        $data['totalDeliveryComplete']  = Parcel::whereRaw('delivery_rider_id = ? and status in (21, 22, 25)', [$rider_id])->count();
        $data['totalDeliveryPending']   = Parcel::whereRaw('delivery_rider_id = ? and status in (23)', [$rider_id])->count();
        $data['totalDeliveryCancel']    = Parcel::whereRaw('delivery_rider_id = ? and status in (20)', [$rider_id])->count();

        $total_ecourier_collection          = Parcel::whereRaw('delivery_rider_id = ? and delivery_type in (1,2)', [$rider_id] )->sum('customer_collect_amount');
        $data['ecourierTotalCollectAmount'] = number_format((float) ($total_ecourier_collection), 2, '.', '');

        $ecourier_collection_paid_to_branch = Parcel::whereRaw('delivery_rider_id = ? and delivery_type in (1,2) and status >= ?', [$rider_id, 25] )->sum('customer_collect_amount');
        $data['ecourierPaidToBranch']       = number_format((float) $ecourier_collection_paid_to_branch, 2, '.', '');

        $data['ecourierBalanceCollectAmount'] = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_branch), 2, '.', '');

        //total
        // $data['a_totalPickupRequest']      = Parcel::whereRaw('pickup_rider_id = ? and status in (6)', [$rider_id])->count();
        $data['a_totalPickupPending']     = Parcel::whereRaw('pickup_rider_id = ? and status in (8)', [$rider_id])->count();
        $data['a_totalPickupComplete']      = Parcel::whereRaw('pickup_rider_id = ? and status not in (5,6,7,8,9)', [$rider_id])->count();

        $data['a_totalDeliveryRequest']      = Parcel::whereRaw('delivery_rider_id = ? and status in (17)', [$rider_id])->count();
        $data['a_totalDeliveryPending']     = Parcel::whereRaw('delivery_rider_id = ? and status in (17,19)', [$rider_id])->count();
        $data['a_totalDeliveryComplete']      = Parcel::whereRaw('delivery_rider_id = ? and (status in (21,22,25) or delivery_type in (1,2))', [$rider_id])->whereDate('delivery_rider_date', '>=', Carbon::now()->subDays(30))->count();

        $data['a_totalReturnRequest']      = Parcel::whereRaw('return_rider_id = ? and status in (31)', [$rider_id])->count();
        $data['a_totalReturnPending']      = Parcel::whereRaw('return_rider_id = ? and status in (33)', [$rider_id])->count();
        
        $data['a_totalPickupRequest']    = ParcelPickupRequest::where('total_complete_parcel', 0)->whereRaw('(rider_id = ? )', [$rider_id])->count();

        
        // New Api 
        $data['sallary']                  = Rider::where('id', $rider_id)->pluck('salary')->first();
        $data['joiningdate']              = Rider::where('id', $rider_id)->pluck('date')->first();



        return response()->json([
            'success'                       => 200,
            'message'                       => "Merchant Dashboard.",
            'dashboard_count'               => $data,
        ], 200);

    }

}

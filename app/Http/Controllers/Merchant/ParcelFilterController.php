<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\Upazila;
use App\Models\WeightPackage;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;

class ParcelFilterController extends Controller
{


    public function filterParcelList($type)
    {


        $current_date       = date("Y-m-d");
        //        $current_date       = "2021-07-25";
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Parcel Filter List';
        $data['collapse']   = 'sidebar-collapse';
        $merchant_id = auth()->guard('merchant')->user()->id;
        if ($type == 'total_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->with('district', 'area')->get();
        
            
        // }elseif ($type == 'delivered_parcel') {
        //     $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        //         ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
        //         ->with('district', 'area')->get();
        
            
        // }
        } elseif ($type == 'delivered_parcel') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->where(function ($query) {
            $query->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
                  ->orWhereIn('status', [21, 22]);
        })
        ->with('district', 'area')
        ->get();
}

        
        elseif ($type == 'cancelled_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
                ->with('district', 'area')->get();
        
        }elseif ($type == 'pending_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
             //->whereBetween('status', [4, 24])
             ->where(function ($query) {
              $query->whereBetween('status', [10, 24])
              ->whereNotIn('status', [21, 22])
               ->orWhere('delivery_type', 3);
                })
            //whereRaw('status < ?', [25])
         // ->whereRaw('(status >3 and status <25) or (status = 1)')
                ->with('district', 'area')->get();
        
            
        }elseif ($type == 'pickup_pending') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('status >= ? AND status <= ? AND status != ?', [1, 9, 3])

                // ->whereRaw('status = ?',  [1])
                ->with('district', 'area')->get();
        
            
        } elseif ($type == 'today_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('pickup_branch_date = ? ', [date("Y-m-d")])
            ->with('district', 'area')->get();
        
    } elseif ($type == 'today_total_delivery_parcel') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->where(function ($query) {
            $query->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
                  ->orWhereIn('status', [21, 22]);
        })
        // ->whereRaw('parcel_date = ?', [date("Y-m-d")])
        ->where(function($query) {
    $query->whereDate('delivery_branch_date', date('Y-m-d'))
          ->orWhereDate('delivery_rider_date', date('Y-m-d'));
})

        ->with('district', 'area')
        ->get();

            
        // } elseif ($type == 'today_total_delivery_parcel') {
        //     $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        //     ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
        //     ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
        //      ->with('district', 'area')->get();
         
            
        }elseif ($type == 'today_delivery_cancel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
            ->whereRaw('delivery_branch_date = ? ', [date("Y-m-d")])
            ->with('district', 'area')->get();
         
            
        }elseif ($type == 'today_partial_delivered') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->whereRaw('status >= ? and delivery_type in (?)', [25, 2])
        ->whereRaw('delivery_branch_date = ?', [date("Y-m-d")])
        ->with('district', 'area')
        ->get();
}

elseif ($type == 'today_return') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->whereRaw('status >= ? and delivery_type in (?)', [36, 4])
        ->whereRaw('return_branch_date = ?', [date("Y-m-d")])
        ->with('district', 'area')
        ->get();
}

elseif ($type == 'today_return_process') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->whereRaw('status >= ? and delivery_type in (?)', [26, 4])
        ->whereRaw('return_branch_date = ?', [date("Y-m-d")])
        ->with('district', 'area')
        ->get();
}elseif ($type == 'total_return_parcel') {
    $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
        ->where('status', '>=', 36)
        ->whereIn('delivery_type', [4])
        ->with('district', 'area')
        ->get();
}

 else {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->with('district', 'area')->get();
        }


        $data['type']    = $type;

        return view('merchant.parcel.parcelFilterList', $data);
    }
}

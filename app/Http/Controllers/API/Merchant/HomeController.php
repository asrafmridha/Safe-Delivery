<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Parcel;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function dashboard(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $data = [];
        $data['total_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->count();

        $data['total_cancel_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->where('status', 3)
            ->count();

        $data['total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [3, 11])
            ->count();

        $data['total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
        ->whereRaw('(status != ? and status >= ? and status <= ?) and (delivery_type is null or delivery_type = "" or delivery_type = ?)', [3, 11, 24, 3])

            // ->whereRaw('status >= ? and status <= ? and (delivery_type is null or delivery_type = "" or delivery_type = "3")', [ 4, 24])
            ->count();

        /*$data['total_waiting_delivery_parcel']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "" or delivery_type in (?))', [3,11,24,3])
                                                ->count();*/
        /*
                $data['total_delivery_parcel']          = Parcel::where('merchant_id', $merchant_id)
                                                        ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3,1,2,3,4])
                                                        ->count();*/

        $data['total_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
            ->count();

        $data['total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5])
            ->count();

        $data['total_partial_delivery_complete'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?) ', [25, 2])
            ->count();

        $data['total_pending_delivery'] = Parcel::where('merchant_id', $merchant_id)
        
            ->whereRaw('status > 11 and delivery_type in (?)', [3])
            ->count();
            
        $data['in_transit_parcel'] = Parcel::where('merchant_id', $merchant_id)
             ->whereRaw('status > ? and status < ?', [11, 15])
            ->count();
            
        $data['total_waiting_delivery_parcel'] += $data['total_pending_delivery'];

        $data['total_return_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
            ->count();

        $data['total_return_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4])
            ->count();

        $data['total_pending_collect_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 4])
            ->sum('merchant_paid_amount');

//        $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
//                                                    ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
//                                                    ->toSql();
//
//        dd($merchant_id, $data['total_pending_collect_amount'] );


        $data['total_collect_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5])
            ->sum('merchant_paid_amount');
            
            
            
         $data['total_collect_amount_from_customer'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) ', [25, 1, 2])
            ->sum('customer_collect_amount');
            
             $data['total_customer_collected_amount']      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?)', [1,2])
            ->sum('customer_collect_amount');
            

            $data['total_charge']      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?,?)', [1,2,4])
            ->sum('total_charge');
            
            $data['total_customer_collected_amount'] -= $data['total_charge'];
            

        // ================== new codes by Humayun ===============
        $total_customer_collect_amount      = Parcel::where('merchant_id', $merchant_id)
        ->where('status', '>=',25)
        ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1,2,2,4,6,0])
        ->sum('customer_collect_amount');
        
        
        

        $total_charge_amount                = Parcel::where('merchant_id', $merchant_id)
        ->where('status', '>=',25)
        ->whereRaw('delivery_type in (?,?,?) and payment_type in (?,?) and payment_request_status = ?', [1,2,4,2,6,0])
        ->sum('total_charge');

        // Balance Amount
        $data['total_pending_payment'] = number_format($total_customer_collect_amount - $total_charge_amount, 2, '.', '');
        // ================== new codes by Humayun End ===============
        
        
        
          $data['news'] = Notice::whereRaw('type = 2 and publish_for IN (0,2)')->orderBy('id', 'DESC')->first();
        
        
        

        return response()->json([
            'success' => 200,
            'message' => "Merchant Dashboard.",
            'dashboard_count' => $data,
        ], 200);

    }

    public function viewNews(Request $request)
    {
        $news_id = $request->input('news_id');
        $data['news'] = Notice::where('id', $news_id)->first();
        return response()->json([
            'success' => 200,
            'message' => "News view",
            'data' => $data,
        ], 200);
    }

}

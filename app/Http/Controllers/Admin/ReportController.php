<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu'] = '';
        $data['child_menu'] = 'report';
        $data['page_title'] = 'Report';
        $data['merchants'] = Merchant::all();
        $data['branches'] = Branch::all();

        $data['merchant_id'] = $merchant_id = $request->input('merchant_id');
        $data['branch_id'] = $branch_id = $request->input('branch_id');
        $data['from_date'] = $from_date = $request->input('from_date') ?? date('Y-m') . "-01";
        $data['to_date'] = $to_date = $request->input('to_date') ?? date('Y-m-d');
//dd($merchant_id);

        $condition = '';
        $pickup_branch = '';
        $delivery_branch = '';
        $return_branch = '';
        $branch_condition = '';
        if ($request->has('merchant_id') && $merchant_id != 0 && $merchant_id != '0') {
            $condition .= " and merchant_id = " . $merchant_id;
        }

        if ($request->has('branch_id') && $branch_id != 0 && $branch_id != '0') {
            $branch_condition .= " and ( pickup_branch_id = " . $branch_id . " or delivery_branch_id = " . $branch_id . "  or return_branch_id = " . $branch_id . " )";
            $pickup_branch .= " and pickup_branch_id = " . $branch_id;
            $delivery_branch .= " and delivery_branch_id = " . $branch_id;
            $return_branch .= " and return_branch_id = " . $branch_id;
        }

        //test start
        /*
                $array1 = Parcel::whereBetween('pickup_branch_date', [$from_date, $to_date])
                    ->whereRaw('status >= ? ' . $condition . $pickup_branch, [11])->select('id')->get()->toArray();
                $array2=[];
                $total_parcel_delivered = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
                    ->whereRaw('status >= ? and delivery_type in (?,?)' . $condition . $delivery_branch, [25, 1, 2])->select('id')->get()->toArray();
                $total_parcel_return = Parcel::whereBetween('return_branch_date', [$from_date, $to_date])
                    ->whereRaw('status >= ?  and delivery_type in (?) ' . $condition . $return_branch, [11, 4])->select('id')->get()->toArray();
                $total_parcel_waiting_for_delivery = Parcel::whereBetween('date', [$from_date, $to_date])
                    ->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))' . $condition)->select('id')->get()->toArray();
                $array2=array_merge($array2,$total_parcel_delivered);
                $array2=array_merge($array2,$total_parcel_return);
                $array2=array_merge($array2,$total_parcel_waiting_for_delivery);
                $temp=[];
                foreach ($array2 as $item){
                    $temp[$item['id']]=$item['id'];
                }
                foreach ($array1 as $value){
                    if (!key_exists($value['id'],$temp)){
                        dd($value['id']);
                    }
                }
                dd('OK');*/
        //test end

//        $data['total_parcel'] = Parcel::whereBetween('date', [$from_date, $to_date])->whereRaw('1' . $condition . $branch_condition)->count();

        $data['total_delivery_assigned_parcel'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= 11 ' . $condition . $delivery_branch)->count();
            
        $data['total_delivery_assigned_parcel_amounts'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= 11 ' . $condition . $delivery_branch)->sum('total_collect_amount');

        $data['total_parcel_pickup'] = Parcel::whereBetween('pickup_branch_date', [$from_date, $to_date])
            ->whereRaw('status >= ? ' . $condition . $pickup_branch, [11])->count();
            
        $data['total_parcel_pickup_amounts'] = Parcel::whereBetween('pickup_branch_date', [$from_date, $to_date])
            ->whereRaw('status >= ? ' . $condition . $pickup_branch, [11])->sum('total_collect_amount');

        $data['total_parcel_delivered'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?,?)' . $condition . $delivery_branch, [25, 1, 2])->count();
            
         $data['total_parcel_delivered_amounts'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?,?)' . $condition . $delivery_branch, [25, 1, 2])->sum('total_collect_amount');

        $data['total_full_delivered'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 1])->count();
            
        $data['total_full_delivered_amounts'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 1])->sum('total_collect_amount');

        $data['total_partial_delivered'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 2])->count();
        
        $data['total_partial_delivered_amounts'] = Parcel::whereBetween('delivery_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 2])->sum('total_collect_amount');

        $data['total_delivery_rescheduled'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 3])->count();
            
        $data['total_delivery_rescheduled_amounts'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 3])->sum('total_collect_amount');

        $data['total_delivery_returned'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 4])->count();
            
        $data['total_delivery_returned_amounts'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= ? and delivery_type in (?)' . $condition . $delivery_branch, [25, 4])->sum('total_collect_amount');

        $data['total_parcel_return'] = Parcel::whereBetween('return_branch_date', [$from_date, $to_date])
            ->whereRaw('status >= ?  and delivery_type in (?) ' . $condition . $return_branch, [11, 4])->count();

        $data['total_parcel_return_amounts'] = Parcel::whereBetween('return_branch_date', [$from_date, $to_date])
            ->whereRaw('status >= ?  and delivery_type in (?) ' . $condition . $return_branch, [11, 4])->sum('total_collect_amount');
            
        $data['total_merchant_cancel'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status = ? ' . $condition, [3])->count();
            
        $data['total_merchant_cancel_amounts'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status = ? ' . $condition, [3])->sum('total_collect_amount');

        $data['total_merchant_pickup_request'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status = ? ' . $condition, [1])->count();
            
        $data['total_merchant_pickup_request_amounts'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status = ? ' . $condition, [1])->sum('total_collect_amount');

        $data['total_parcel_waiting_for_pickup'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status != ? and status < ? ' . $condition, [3, 11])->count();
            
        $data['total_parcel_waiting_for_pickup_amounts'] = Parcel::whereBetween('date', [$from_date, $to_date])
            ->whereRaw('status != ? and status < ? ' . $condition, [3, 11])->sum('total_collect_amount');

        $data['total_parcel_waiting_for_delivery'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))' . $condition.$delivery_branch)->count();
            
        $data['total_parcel_waiting_for_delivery_amounts'] = Parcel::whereBetween('parcel_date', [$from_date, $to_date])
            ->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))' . $condition.$delivery_branch)->sum('total_collect_amount');;

        return view('admin.account.report', $data);
    }
}

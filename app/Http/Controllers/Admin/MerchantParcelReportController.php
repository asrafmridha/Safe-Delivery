<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function RingCentral\Psr7\str;

class MerchantParcelReportController extends Controller
{





 public function todayPickupParcelReport(Request $request)
    {
        $data = [];
        $data['main_menu']  = 'report';
        $data['child_menu'] = 'merchant-pickup-parcel-report';
        $data['page_title'] = 'merchant-pickup-parcel-report';

        $data['merchants'] = Merchant::all();
        return view('admin.reports.merchant_reports.merchantPickupParcelReport', $data);
    }

    public function getTodayPickupParcelReport(Request $request)
    {

        $data = [];
        $data['main_menu']  = 'report';
        $data['child_menu'] = 'merchant-pickup-parcel-report';
        $data['page_title'] = 'merchant-pickup-parcel-report';
        $data['merchants'] = Merchant::all();

        $from_date = $request->from_date;
        $to_date = $request->to_date;


        $data['from_date']  = $from_date;
        $data['to_date']    = $to_date;




        return view('admin.reports.merchant_reports.filterMerchantPickupParcelReport', $data);
    }


    public function monthlyParcelReport()
    {
        $data = [];
        $data['main_menu']  = 'report';
        $data['child_menu'] = 'merchant-parcel-report';
        $data['page_title'] = 'Merchant Parcel Report';

        $current_date = date("Y-m-d");
        $current_month_first_date   = date("Y-m-01");
        $current_month_last_date = date("Y-m-t");

        $from_date  = date("Y-m-d", strtotime("-1 month", strtotime($current_date)));
        $to_date    = $current_date;

        $data['from_date']  = $from_date;
        $data['to_date']    = $to_date;

        $data['merchants'] = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();

//        $sql = "select m.id, m.m_id, m.company_name, p.date, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 and p.status >= 11 and p.date BETWEEN '{$from_date}' and '{$to_date}' group by p.date, m.id, m.m_id, m.company_name";

        $sql = "SELECT m.id, m.m_id, m.company_name, pl.date, COUNT(pl.parcel_id) AS count_parcel FROM merchants m JOIN parcels p ON p.merchant_id = m.id JOIN parcel_logs pl ON pl.parcel_id = p.id WHERE m.status = 1 AND pl.status = 11 AND pl.date BETWEEN '{$from_date}' AND '{$to_date}' GROUP BY pl.date, m.id, m.m_id, m.company_name";
//        dd($sql);

        $merchant_with_parcel = DB::select(DB::raw($sql));

        $final_array = [];
        if($merchant_with_parcel) {
            foreach ($merchant_with_parcel as $mparcel) {
                $final_array[$mparcel->id.'_'.$mparcel->date] = $mparcel->count_parcel;
            }
        }

        $data['final_array'] = $final_array;

       //dd($sql, $merchant_with_parcel, $final_array);

        $date_array = [];
        $full_date_array = [];
        for($iDate = $from_date;  $iDate <= $to_date; ) {
            $date_array[$iDate] = date("d", strtotime($iDate));

//            $full_date_array[] = $iDate;
            $full_date_array[$iDate] = 0;

            $iDate = date("Y-m-d", strtotime("+1 day", strtotime($iDate)));

        }

        $data['date_array'] = $date_array;
        $data['full_date_array'] = $full_date_array;

        //dd($date_array, $full_date_array);

        return view('admin.reports.merchant_reports.merchantParcelReport', $data);
    }

    public function getMonthlyParcelReport(Request $request)
    {
        $data = [];
        if($request->ajax()) {
            $current_date = date("Y-m-d");
            $current_month_first_date   = date("Y-m-01");
            $current_month_last_date = date("Y-m-t");

            if("" != $request->merchant_id && $request->merchant_id != 0) {
                $merchant_where = " and m.id = {$request->get('merchant_id')} ";
                $merchants      = Merchant::where('id', $request->get('merchant_id'))->get();
            }else{
                $merchant_where = "";
                $merchants      = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();
            }

            //dd($merchant_where);

            if("" != $request->from_date && "" != $request->to_date) {

                $from_date  = $request->get('from_date');
                $to_date    = $request->get('to_date');

            } else {

                $from_date = date("Y-m-d", strtotime("-1 month", strtotime($current_date)));
                $to_date = $current_date;

            }

            $data['from_date']  = $from_date;
            $data['to_date']    = $to_date;

            $data['merchants'] = $merchants;

//            $sql = "select m.id, m.m_id, m.company_name, p.date, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 {$merchant_where} and p.status >= 11 and p.date BETWEEN '{$from_date}' and '{$to_date}' group by p.date, m.id, m.m_id, m.company_name";

            $sql = "SELECT m.id, m.m_id, m.company_name, pl.date, COUNT(pl.parcel_id) AS count_parcel FROM merchants m JOIN parcels p ON p.merchant_id = m.id JOIN parcel_logs pl ON pl.parcel_id = p.id WHERE m.status = 1 {$merchant_where} AND pl.status = 11 AND pl.date BETWEEN '{$from_date}' AND '{$to_date}' GROUP BY pl.date, m.id, m.m_id, m.company_name";
            $merchant_with_parcel = DB::select(DB::raw($sql));

            $final_array = [];
            if($merchant_with_parcel) {
                foreach ($merchant_with_parcel as $mparcel) {
                    $final_array[$mparcel->id.'_'.$mparcel->date] = $mparcel->count_parcel;
                }
            }

            $data['final_array'] = $final_array;

            //dd($sql, $merchant_with_parcel, $final_array);

            $date_array = [];
            $full_date_array = [];
            for($iDate = $from_date;  $iDate <= $to_date; ) {
                $date_array[$iDate] = date("d", strtotime($iDate));

//                $full_date_array[] = $iDate;
                $full_date_array[$iDate] = 0;

                $iDate = date("Y-m-d", strtotime("+1 day", strtotime($iDate)));

            }

            $data['date_array'] = $date_array;
            $data['full_date_array'] = $full_date_array;
        }

        //dd($date_array, $full_date_array);

        return view('admin.reports.merchant_reports.filterMerchantParcelReport', $data);
    }
}

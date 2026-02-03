<?php

namespace App\Exports;

use App\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MerchantPickupParcelReportExport implements FromView, ShouldAutoSize
{

    private $merchant_id, $from_date, $to_date;

    public function __construct($request)
    {
        $this->merchant_id = $request->merchant_id;
        $this->from_date = $request->from_date;
        $this->to_date = $request->to_date;
    }


    /**
     * @return \Illuminate\View\View
     */
    public function view(): View
    {

        $data = [];
        $current_date = date("Y-m-d");
        $current_month_first_date = date("Y-m-01");
        $current_month_last_date = date("Y-m-t");

        if ("" != $this->merchant_id && $this->merchant_id != 0) {
            $merchant_where = " and m.id = {$this->merchant_id} ";
            $merchants = Merchant::where('id', $this->merchant_id)->get();
        } else {
            $merchant_where = "";
            $merchants = Merchant::where('status', 1)->orderBy('company_name', 'ASC')->get();
        }

        //dd($merchant_where);

        if ("" != $this->from_date && "" != $this->to_date) {

            $from_date = $this->from_date;
            $to_date = $this->to_date;

        } else {

            $from_date = date("Y-m-d", strtotime("-1 month", strtotime($current_date)));
            $to_date = $current_date;

        }

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $data['merchants'] = $merchants;

        // $sql = "select m.id, m.m_id, m.company_name, p.date, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 {$merchant_where} and p.status >= 11 and p.date BETWEEN '{$from_date}' and '{$to_date}' group by p.date, m.id, m.m_id, m.company_name";

        $sql = "SELECT m.id, m.m_id, m.company_name, pl.date, COUNT(pl.parcel_id) AS count_parcel FROM merchants m JOIN parcels p ON p.merchant_id = m.id JOIN parcel_logs pl ON pl.parcel_id = p.id WHERE m.status = 1 {$merchant_where} AND pl.status = 11 AND pl.date BETWEEN '{$from_date}' AND '{$to_date}' GROUP BY pl.date, m.id, m.m_id, m.company_name";
        $merchant_with_parcel = DB::select(DB::raw($sql));

        $final_array = [];
        if ($merchant_with_parcel) {
            foreach ($merchant_with_parcel as $mparcel) {
                $final_array[$mparcel->id . '_' . $mparcel->date] = $mparcel->count_parcel;
            }
        }

        $data['final_array'] = $final_array;

        //dd($sql, $merchant_with_parcel, $final_array);

        $date_array = [];
        $full_date_array = [];
        for ($iDate = $from_date; $iDate <= $to_date;) {
            $date_array[$iDate] = date("d", strtotime($iDate));

            // $full_date_array[] = $iDate;
            $full_date_array[$iDate] = 0;

            $iDate = date("Y-m-d", strtotime("+1 day", strtotime($iDate)));

        }

        $data['date_array'] = $date_array;
        $data['full_date_array'] = $full_date_array;


        return view('admin.reports.merchant_reports.exportMerchantParcelReport', $data);
    }

}

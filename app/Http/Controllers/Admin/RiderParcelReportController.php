<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiderParcelReportController extends Controller
{

    public function deliveryParcelReport()
    {
        $data = [];
        $data['main_menu']  = 'report';
        $data['child_menu'] = 'rider-delivery-parcel-report';
        $data['page_title'] = 'Rider Delivery Parcel Report';
        $data['riders']     = Rider::where('status', 1)->orderBy('name')->get();

        $current_date       = date("Y-m-d");
        $data['start_date'] = $current_date;
        $data['end_date'] = $current_date;
        $sql = "SELECT
                    t1.id,
                    t1.name,
                    t6.name AS branch_name,
                    COALESCE(t2.total_parcel, 0) AS total_parcel,
                    COALESCE(t3.done_parcel, 0) AS done_parcel,
                    COALESCE(t4.pending_parcel, 0) AS pending_parcel,
                    COALESCE(t5.cancel_parcel, 0) AS cancel_parcel,
                    COALESCE(t3.collection_amount, 0) AS collection_amount,
                    t2.parcel_invoices
                FROM
                    riders t1
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(id) AS total_parcel,
                        GROUP_CONCAT(parcel_invoice SEPARATOR ', ') AS parcel_invoices
                    FROM
                        parcels
                    WHERE status >= 16 AND status NOT IN (18, 20) AND delivery_rider_accept_date = '{$current_date}'
                GROUP BY
                    delivery_rider_id
                ) t2
                ON
                    t1.id = t2.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS done_parcel,
                        SUM(customer_collect_amount) AS collection_amount
                    FROM
                        parcels
                    WHERE status >= 25 AND delivery_type IN (1, 2) AND delivery_rider_accept_date = '{$current_date}'
                GROUP BY
                    delivery_rider_id
                ) t3
                ON
                    t1.id = t3.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS pending_parcel
                    FROM
                        parcels
                    WHERE (status >= 16 and status < 24 AND status NOT IN (18, 20)) OR (status = 25 AND delivery_type in (3)) AND delivery_rider_accept_date = '{$current_date}'
                GROUP BY
                    delivery_rider_id
                ) t4
                ON
                    t1.id = t4.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS cancel_parcel
                    FROM
                        parcels
                    WHERE status >= 24 AND delivery_type IN(4) AND delivery_rider_accept_date = '{$current_date}'
                GROUP BY
                    delivery_rider_id
                ) t5
                ON
                    t1.id = t5.delivery_rider_id
                LEFT JOIN branches t6 ON
                    t1.branch_id = t6.id

                WHERE t1.status = 1";

        //echo $sql;
        $rider_with_parcel  = DB::select(DB::raw($sql));
        $data['report_data']    = $rider_with_parcel;
//        echo '<pre>';
//        print_r($rider_with_parcel);
//        dd();

        return view('admin.reports.rider_reports.riderDeliveryParcelReport', $data);

    }

    public function getDeliveryParcelReport(Request $request)
    {
        $data = [];

        if($request->ajax()) {

//            $current_date = ("" != $request->get('action_date')) ? $request->get('action_date') : date("Y-m-d");
            $start_date = ("" != $request->get('start_date')) ? $request->get('start_date') : date("Y-m-d");
            $end_date = ("" != $request->get('end_date')) ? $request->get('end_date') : date("Y-m-d");
            $rider_where  = ("" != $request->rider_id && $request->rider_id != 0) ? " AND t1.id = {$request->get('rider_id')}" : "";
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $sql = "SELECT
                    t1.id,
                    t1.name,
                    t6.name AS branch_name,
                    COALESCE(t2.total_parcel, 0) AS total_parcel,
                    COALESCE(t3.done_parcel, 0) AS done_parcel,
                    COALESCE(t4.pending_parcel, 0) AS pending_parcel,
                    COALESCE(t5.cancel_parcel, 0) AS cancel_parcel,
                    COALESCE(t3.collection_amount, 0) AS collection_amount,
                    t2.parcel_invoices
                FROM
                    riders t1
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(id) AS total_parcel,
                        GROUP_CONCAT(parcel_invoice SEPARATOR ', ') AS parcel_invoices
                    FROM
                        parcels
                    WHERE status >= 16 AND status NOT IN(18, 20) AND delivery_rider_accept_date >= '{$start_date}' AND delivery_rider_accept_date <= '{$end_date}'
                GROUP BY
                    delivery_rider_id
                ) t2
                ON
                    t1.id = t2.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS done_parcel,
                        SUM(customer_collect_amount) AS collection_amount
                    FROM
                        parcels
                    WHERE status >= 25 AND delivery_type IN(1, 2)  AND delivery_rider_accept_date >= '{$start_date}' AND delivery_rider_accept_date <= '{$end_date}'
                    /*WHERE (status >= 25 AND delivery_type in (1,2))  AND parcel_date >= '{$start_date}' AND parcel_date <= '{$end_date}'*/
                    GROUP BY
                    delivery_rider_id
                ) t3
                ON
                    t1.id = t3.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS pending_parcel
                    FROM
                        parcels
                    WHERE (status >= 16 and status < 24 AND status NOT IN(18, 20)) OR (status = 25 AND delivery_type in (3))  AND delivery_rider_accept_date >= '{$start_date}' AND delivery_rider_accept_date <= '{$end_date}'
                GROUP BY
                    delivery_rider_id
                ) t4
                ON
                    t1.id = t4.delivery_rider_id
                LEFT JOIN(
                    SELECT
                        delivery_rider_id,
                        COUNT(*) AS cancel_parcel
                    FROM
                        parcels
                    /*WHERE status >= 24 AND delivery_type IN(4) AND delivery_rider_accept_date <= '{$start_date}'  AND delivery_rider_accept_date >= '{$start_date}' AND delivery_rider_accept_date <= '{$end_date}'*/
                    WHERE status >= 24 AND delivery_type IN(4) AND delivery_rider_accept_date >= '{$start_date}' AND delivery_rider_accept_date <= '{$end_date}'
                GROUP BY
                    delivery_rider_id
                ) t5
                ON
                    t1.id = t5.delivery_rider_id
                LEFT JOIN branches t6 ON
                    t1.branch_id = t6.id

                WHERE t1.status = 1 {$rider_where}";

            //echo $sql;
            $rider_with_parcel = DB::select(DB::raw($sql));
            $data['report_data'] = $rider_with_parcel;
//        echo '<pre>';
//        print_r($rider_with_parcel);
//        dd();

        }
        return view('admin.reports.rider_reports.filterRiderDeliveryParcelReport', $data);
    }
}

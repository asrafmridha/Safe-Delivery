<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\BookingParcel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BookingParcelController extends Controller{


    public function index(){
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcellist';
        $data['page_title'] = 'Booking Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('warehouse.booking_parcel.bookingParcelList', $data);
    }


    public function getBookingParcelList() {
        $warehouseUser = auth()->guard('warehouse')->user();

        $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
            ->where('receiver_warehouse_id', $warehouseUser->warehouse_id)
            ->where('vehicle_warehouse_status', 2)
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()->editColumn('net_amount', function ($data) {
                $total_amount = $data->net_amount + $data->pickup_charge;
                return sprintf("%.2f", $total_amount);
            })
            ->editColumn('delivery_type', function ($data) {
                switch ($data->delivery_type) {
                    case 'hd':$delivery_type  = "HD"; $class="success"; break;
                    case 'thd':$delivery_type  = "THD"; $class="info"; break;
                    case 'od':$delivery_type  = "OD"; $class="primary"; break;
                    case 'tod':$delivery_type  = "TOD"; $class="warning"; break;
                    default:$delivery_type = "None"; $class = "danger";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';
            })
            ->editColumn('status', function ($data) {
                // $warehouse_name = ($data->warehouse_tbls) ? $data->warehouse_tbls->wh_name : 'Default';
                $receiver_warehouse_name = ($data->receiver_warehouses) ? $data->receiver_warehouses->name : 'Warehouse';

                switch ($data->status) {
                    case 0:$status_name    = "Parcel Reject from operation"; $class  = "danger";break;
                    case 1:$status_name    = "Confirmed Booking"; $class  = "success";break;
                    case 2:$status_name    = "Vehicle Assigned"; $class   = "success";break;
                    case 3:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 4:$status_name    = "Warehouse Received Parcel"; $class  = "success";break;
                    case 5:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 6:$status_name    = "Wait for destination branch receive"; $class  = "success";break;
                    case 7:$status_name    = "Destination branch received parcel"; $class  = "success";break;
                    //    case 8:$status_name  = "Delivery Branch Received"; $class  = "success";break;
                    //    case 9:$status_name  = "Delivery Branch Reject"; $class  = "success";break;
                    //    case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                    //    case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = "";
                // $button = '<a href="#" class="btn btn-success btn-sm" title="Print Booking Parcel" target="_blank">
                //     <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                // $button .= '&nbsp; <a href="#" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                return $button;
            })
            ->rawColumns(['delivery_type', 'status', 'action'])
            ->make(true);
    }


    public function viewBookingParcel(Request $request, BookingParcel $booking_parcel) {
        $booking_parcel->load(['sender_branch' => function($query){$query->select('id', 'name');},
            'receiver_branch' => function($query){ $query->select('id','name'); }, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items'
        ]);
        return view('warehouse.booking_parcel.viewParcel', compact('booking_parcel'));
    }

}

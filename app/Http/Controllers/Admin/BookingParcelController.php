<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\BookingParcel;
use App\Models\Branch;
use App\Models\District;
use App\Models\Division;
use App\Models\ItemCategory;
use App\Models\Merchant;
use App\Models\Rider;
use App\Models\Unit;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BookingParcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcellist';
        $data['page_title'] = 'Booking Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->orderBy('name', 'ASC')->get();

        return view('admin.booking_parcel.bookingParcelList', $data);
    }

    public function getBookingParcelList(Request $request) {
//        if(auth()->guard('admin')->user()->type != 1) {
//            $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
//                ->whereNotIn('status', [1, 8])
//                //            ->whereNotIn('vehicle_warehouse_status', [0])
//                ->select();
//        }else {
//            $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
//                ->select();
//        }

        $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
                ->where(function ($query) use ($request) {
                    $branch_id = $request->input('branch_id');
                    $booking_parcel_type = $request->input('booking_parcel_type');
                    $booking_delivery_type = $request->input('delivery_type');
                    $booking_status = $request->input('status');
                    $from_date  = $request->input('from_date');
                    $to_date    = $request->input('to_date');
                    if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '') {
                        $query->where('sender_branch_id', $branch_id);
                    }
                    if ($request->has('booking_parcel_type') && !is_null($booking_parcel_type) && $booking_parcel_type != '') {
                        $query->where('booking_parcel_type', $booking_parcel_type);
                    }
                    if ($request->has('delivery_type') && !is_null($booking_delivery_type) && $booking_delivery_type != '') {
                        $query->where('delivery_type', $booking_delivery_type);
                    }
                    if ($request->has('status') && !is_null($booking_status) && $booking_status != '') {
                        $query->where('status', $booking_status);
                    }
                    if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                        $query->whereDate('booking_date', '>=', $from_date);
                    }
                    if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                        $query->whereDate('booking_date', '<=', $to_date);
                    }
                })->get();

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
//                $warehouse_name = ($data->warehouse_tbls) ? $data->warehouse_tbls->wh_name : 'Default';
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
                    case 8:$status_name    = "Parcel Complete Delivery"; $class = "success";break;
                    case 9:$status_name    = "Parcel Return Delivery"; $class = "success";break;
                    //    case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                    //    case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('admin.bookingParcel.printBookingParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Booking Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                if($data->status != 8) {
                    $button .= '&nbsp; <a href="' . route('admin.bookingParcel.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                }
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

        //dd($booking_parcel);

        // $parcelLogs = BookingParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
        //     ->where('parcel_id', $booking_parcel->id)->get();

        return view('admin.booking_parcel.viewParcel', compact('booking_parcel'));
    }

    public function printBookingParcel(Request $request, BookingParcel $booking_parcel) {
        $page_title = 'Print Booking Parcel';
        $booking_parcel->load(['sender_branch' => function ($query) {$query->select('id', 'name');},
            'receiver_branch'                      => function ($query) {$query->select('id', 'name');}, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items',
        ]);

//dd($booking_parcel);

// $parcelLogs = BookingParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
        //     ->where('parcel_id', $booking_parcel->id)->get();

        return view('admin.booking_parcel.printParcel', compact('booking_parcel', 'page_title'));
    }


    public function edit(BookingParcel $bookingParcel)
    {
        $bookingParcel->load(['sender_branch' => function($query){$query->select('id', 'name');},
            'receiver_branch' => function($query){ $query->select('id','name'); }, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items'
        ]);


        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcel';
        $data['page_title'] = 'Booking Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['bookingParcel']  = $bookingParcel;


        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['categories'] = ItemCategory::where([
            ['status', '=', 1],
        ])->get();

        $data['merchants'] = Merchant::where([
            ['status', '=', 1],
        ])->where([
            ['branch_id', '=', $bookingParcel->sender_branch_id],
        ])->get();

        $data['riders'] = Rider::where([
            ['status', '=', 1],
        ])->where([
            ['branch_id', '=', $bookingParcel->sender_branch_id],
        ])->get();


        $data['units'] = Unit::where([
            ['status', '=', 1],
        ])->get();

        $data['divisions'] = Division::where([
            ['status', '=', 1],
        ])->get();

        $data['sender_districts'] = District::where([
            ['status', '=', 1]
        ])->where([
            ['division_id', '=', $bookingParcel->sender_division_id]
        ])->get();

        $data['receiver_districts'] = District::where([
            ['status', '=', 1]
        ])->where([
            ['division_id', '=', $bookingParcel->receiver_division_id]
        ])->get();

        $data['sender_upazilas'] = Upazila::where([
            ['status', '=', 1]
        ])->where([
            ['district_id', '=', $bookingParcel->sender_district_id]
        ])->get();

        $data['receiver_upazilas'] = Upazila::where([
            ['status', '=', 1]
        ])->where([
            ['district_id', '=', $bookingParcel->receiver_district_id]
        ])->get();

        $data['sender_areas'] = Area::where([
            ['status', '=', 1]
        ])->where([
            ['upazila_id', '=', $bookingParcel->sender_thana_id]
        ])->get();

        $data['receiver_areas'] = Area::where([
            ['status', '=', 1]
        ])->where([
            ['upazila_id', '=', $bookingParcel->receiver_thana_id]
        ])->get();

        return view('admin.booking_parcel.edit', $data);
    }


    public function update(Request $request, $id) {

        $booking_parcel = BookingParcel::find($id);

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'booking_parcel_type'  => 'required',
            'sender_phone'         => 'required',
            'sender_address'       => 'required',
            'receiver_phone'       => 'required',
            'receiver_address'     => 'required',
            'receiver_branch_id'   => 'required',
            'delivery_type'        => 'required',
            'total_amount'         => 'required',
            'grand_amount'         => 'required',
            'net_amount'           => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors'  => $validator->errors(),
            ];
            return response()->json($response);
        }

        $inputs_update  = [
            'booking_parcel_type'    => $inputs['booking_parcel_type'],
            'merchant_id'            => $inputs['merchant_id'] ?? 0,
            'rider_id'               => $inputs['rider_id'] ?? 0,
            'sender_phone'           => $inputs['sender_phone'],
            'sender_address'         => $inputs['sender_address'],
            'sender_name'            => $inputs['sender_name'],
            'sender_nid'             => $inputs['sender_nid'],
            'sender_division_id'     => $inputs['sender_division_id'],
            'sender_district_id'     => $inputs['sender_district_id'],
            'sender_thana_id'        => $inputs['sender_thana_id'],
            'sender_area_id'         => $inputs['sender_area_id'],
            'receiver_phone'         => $inputs['receiver_phone'],
            'receiver_address'       => $inputs['receiver_address'],
            'receiver_name'          => $inputs['receiver_name'],
            'receiver_division_id'   => $inputs['receiver_division_id'],
            'receiver_district_id'   => $inputs['receiver_district_id'],
            'receiver_thana_id'      => $inputs['receiver_thana_id'],
            'receiver_area_id'       => $inputs['receiver_area_id'],
            'receiver_branch_id'     => $inputs['receiver_branch_id'],
            'note'                   => $inputs['note'],

            'collection_amount'      => $inputs['collection_amount'] ?? 0,
            'cod_amount'             => ($request->get('booking_parcel_type') == "condition") ? $inputs['cod_amount'] : 0,

            'discount_percent'       => $inputs['discount_percent'] ?? 0,
            'discount_amount'        => $inputs['discount_amount'] ?? 0,
            'net_amount'             => $inputs['net_amount'],
            'pickup_charge'          => $inputs['pickup_charge'] ?? 0,
            'paid_amount'            => $inputs['paid_amount'] ?? 0,
            'due_amount'             => $inputs['due_amount'] ?? 0,
            'updated_admin_user_id'  => auth()->guard('admin')->user()->id,

        ];

        DB::beginTransaction();
        try {
            $parcel_update        = $booking_parcel->update($inputs_update);
//            $parcel_log_save    = $parcel_save->booking_parcel_logs()->create($input_logs);
//            $parcel_item_save   = $parcel_save->booking_items()->saveMany($items_array);

//            if("cash" == $request->get('booking_parcel_type')) {
//                $parcel_payment_save    = $parcel_save->booking_parcel_payment_details()->create($input_payments);
//            }

            DB::commit();
            $response = [
                'success' => true,
                'errors'  => [],
            ];

            return response()->json($response);

        } catch (\Exception$ex) {
            DB::rollback();
            $response = [
                'success' => false,
                'errors'  => [$ex->getMessage()],
            ];
            // return $ex->getMessage();
            return response()->json($response, 500);
        }

    }


    /** Print Booking Parcel List */
    public function bookingParcelPrintList(Request $request){

        $booking_parcels = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
            ->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $booking_parcel_type = $request->input('booking_parcel_type');
                $booking_delivery_type = $request->input('delivery_type');
                $booking_status = $request->input('status');
                $from_date  = $request->input('from_date');
                $to_date    = $request->input('to_date');
                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '') {
                    $query->where('sender_branch_id', $branch_id);
                }
                if ($request->has('booking_parcel_type') && !is_null($booking_parcel_type) && $booking_parcel_type != '') {
                    $query->where('booking_parcel_type', $booking_parcel_type);
                }
                if ($request->has('delivery_type') && !is_null($booking_delivery_type) && $booking_delivery_type != '') {
                    $query->where('delivery_type', $booking_delivery_type);
                }
                if ($request->has('status') && !is_null($booking_status) && $booking_status != '') {
                    $query->where('status', $booking_status);
                }
                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('booking_date', '>=', $from_date);
                }
                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('booking_date', '<=', $to_date);
                }
            })->get();

        return view('admin.booking_parcel.printBookingParcelList', compact('booking_parcels'));
    }


}

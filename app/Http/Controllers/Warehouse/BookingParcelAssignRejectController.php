<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\BookingParcel;
use App\Models\BookingParcelLog;
use App\Models\BookingVehicle;
use App\Models\BookingVehiclePlist;
use App\Models\Branch;
use App\Models\Vehicle;
use App\Models\Warehouse;
use App\Models\WarehouseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\DataTables;

class BookingParcelAssignRejectController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcelVehiclelist';
        $data['page_title'] = 'Branch Parcel Assign Vehicle List';
        $data['collapse']   = 'sidebar-collapse';
        return view('warehouse.booking_parcel.branchVehicleAssignList', $data);
    }

    public function vehicleAssignList() {
        $model = BookingVehicle::with(['vehicles', 'branches'])
            ->where('vehicle_status', 1)->select();

        return DataTables::of($model)

            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('Y-m-d h:i A');
            })
            ->addColumn('action', function ($data) {

//                $button = '<a href="#" class="btn btn-success btn-sm" title="Print Booking Parcel" target="_blank">

//                    <i class="fas fa-print"></i> </a>';

//                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
//                <i class="fa fa-eye"></i> </button>';
                $button = '&nbsp; <a href="' . route('admin.operationBookingParcel.show', $data->id) . '" class="btn btn-success btn-sm" title="View Parcel"> <i class="fa fa-eye"></i> </a>';

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id) {
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcelVehiclelist';
        $data['page_title'] = 'Branch Parcel Assign Vehicle List';
        $data['collapse']   = 'sidebar-collapse';
        $data['warehouses'] = Warehouse::where([
            'status' => 1,
        ])->get();
        $data['bookingParcel'] = BookingVehiclePlist::with(['booking_parcels.receiver_branch'])
            ->where('master_id', $id)
            ->get();

//        dd($data['bookingParcel']);

        return view('admin.booking_parcel.branchVehicleAssignParcelList', $data);
    }

    public function assignVehicleToWarhouse() {
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'assignVehicleToWearhouse';
        $data['page_title'] = 'Parcel Assign Vehicle To Wearhouse';
        $data['collapse']   = 'sidebar-collapse';
        $data['warehouses'] = Warehouse::where([
            'status' => 1,
        ])->get();

        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['vehicles'] = Vehicle::where([
            ['status', '=', 1],
        ])->get();
        return view('admin.booking_parcel.assignVehicleToWearhouse', $data);
    }

    public function getParcelListForVehicleToWareHouseAssign(Request $request) {

        if ($request->booking_branch_id != 'all') {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('vehicle_id', $request->vehicle_id)
                ->where('sender_branch_id', $request->booking_branch_id)
                ->whereIn('status', [0, 2])
                ->whereNotIn('vehicle_warehouse_status', [0, 2])
                ->get();
        } else {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('vehicle_id', $request->vehicle_id)
                ->whereIn('status', [0, 2])
                ->whereNotIn('vehicle_warehouse_status', [0, 2])
                ->get();
        }

        //dd($data['bookingParcel']);
        $value_data = '';

        foreach ($bookingParcel as $value) {
            $value_data .= '<tr style="text-align:center;">
                            <td><Input name="parcel_id[]" class="parcel_item" type="checkbox" value="' . $value->id . '"></td>
                            <td>' . $value->parcel_code . '</td>
                            <td>' . $value->sender_phone . '</td>
                            <td>' . $value->receiver_branch->name . '</td>
                            <td>' . sprintf("%.2f", ($value->net_amount + $value->pickup_charge)) . '</td>
                            <td >' . strtoupper($value->delivery_type) . '</td>
                            <td ><a href="javascript:void(0)" title="Reject" id="rejectParcel_' . $value->id . '" class="rejectParcel text-danger" data-parcel_id="' . $value->id . '"><i class="fa fa-times"></i></a></td>
                        </tr>';
        }

        return response()->json($value_data);
        //return view('admin.booking_parcel.branchVehicleAssignParcelList', $data);
    }

    public function confirmWarehouseAssign(Request $request) {

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'vehicle_id'          => 'required',
                'warehouse_id'        => 'required',
                'total_assign_parcel' => 'required',
            ], [
                'total_assign_parcel.required' => 'Please checked minimum 1 parcel for Assign',
            ]);

            if ($validator->fails()) {

                $response = [
                    'success' => false,
                    'errors'  => $validator->errors(),
                ];
                return response()->json($response);
            }

            $user_id       = auth()->guard('admin')->user()->id;
            $currdate_time = date("Y-m-d H:i:s");

            $data_parcel_update = [
                'status'                   => 3,
                'vehicle_warehouse_status' => 1,
                'vehicle_id'               => $request->input('vehicle_id'),
                'receiver_warehouse_id'    => $request->input('warehouse_id'),
                'receiver_warehouse_type'  => $request->input('warehouse_type'),
                'note'                     => $request->get('note'),
                'updated_admin_user_id'    => $user_id,
                'updated_at'               => $currdate_time,

            ];

            $data_parcel_logs = [];
            $booking_ids      = [];

            if ($request->total_assign_parcel > 0) {

                foreach ($request->get('parcel_id') as $k => $v_id) {
                    $booking_id    = $v_id;
                    $booking_ids[] = $booking_id;

                    $data_parcel_logs[] = [
                        'booking_id'               => $booking_id,
                        'vehicle_id'               => $request->get('vehicle_id'),
                        'receiver_warehouse_id'    => $request->get('warehouse_id'),
                        'receiver_warehouse_type'  => $request->get('warehouse_type'),
                        'note'                     => $request->get('note'),
                        'status'                   => 3,
                        'vehicle_warehouse_status' => 1,
                        'created_admin_user_id'    => $user_id,
                        'updated_admin_user_id'    => $user_id,
                        'created_at'               => $currdate_time,
                        'updated_at'               => $currdate_time,
                    ];
                }

            } else {
                $error = [
                    'parcel_item' => "You did't select parcel, please try again",
                ];
                $response = [
                    'success' => false,
                    'errors'  => $error,
                ];
                return response()->json($response);
            }

            //dd($data_parcel_update, $data_parcel_logs, $booking_ids);

            DB::beginTransaction();
            try {
                $data_update = BookingParcel::whereIn('id', $booking_ids)->update($data_parcel_update);
                $data_create = BookingParcelLog::insert($data_parcel_logs);

                DB::commit();
                $response = [
                    'success' => true,
                    'errors'  => [],
                ];
                return response()->json($response);

            } catch (\Exception$ex) {
                DB::rollBack();
                $response = [
                    'success' => false,
                    'errors'  => [$ex->getMessage()],
                ];
                return response()->json($response);
            }

        }

    }

    public function rejectParcelFromVehicle(Request $request) {

        if ($request->ajax()) {

            $admin_user_id  = auth()->guard('admin')->user()->id;
            $booking_id     = $request->get('booking_id');
            $booking_parcel = BookingParcel::find($booking_id);

            $booking_update = [
                'status'                   => 0,
                'vehicle_id'               => 0,
                'vehicle_warehouse_status' => 0,
                'updated_admin_user_id'    => $admin_user_id,
            ];

            $booking_parcel_log = [
                'booking_id'               => $booking_id,
                'vehicle_id'               => $booking_parcel->vehicle_id,
                'vehicle_warehouse_status' => $booking_parcel->vehicle_warehouse_status,
                'status'                   => 0,
                'created_admin_user_id'    => $admin_user_id,
                'updated_admin_user_id'    => $admin_user_id,
            ];

//            dd($booking_update, $booking_parcel_log);

            DB::beginTransaction();
            try {
                $update_parcel = $booking_parcel->update($booking_update);
                $log_save      = BookingParcelLog::create($booking_parcel_log);

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
//            return $ex->getMessage();
                return response()->json($response, 500);
            }

        }

    }

    /** After First Warehouse Assign */
    public function bookingParcelOperation() {
        $data                  = [];
        $data['warehouseUser'] = WarehouseUser::with('warehouse')->where('id', auth()->guard('warehouse')->user()->id)->first();
        $data['main_menu']     = 'booking';
        $data['child_menu']    = 'bookingParcelOperation';
        $data['page_title']    = 'Parcel Assign Warehouse To Vehicle & Warehouse';
        $data['collapse']      = 'sidebar-collapse';
        $data['branches']      = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['receiver_branches'] = BookingParcel::with(['receiver_branch' => function ($query) {
            $query->select('id', 'name');
        }])
            ->select('receiver_branch_id')
            ->distinct()
            ->get();

        $data['vehicles'] = Vehicle::where([
            ['status', '=', 1],
        ])->get();

        $data['warehouses'] = Warehouse::where([
            ['status', '=', 1],
        ])->get();
        return view('warehouse.booking_parcel.bookingParcelOperation', $data);
    }

    public function getParcelListForVehicleToWarehouseReceive(Request $request) {

        $warehouseUser = auth()->guard('warehouse')->user();
        if ($request->vehicle_id != 'all') {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('receiver_warehouse_id', $warehouseUser->warehouse_id)
                ->where('vehicle_id', $request->vehicle_id)
                ->whereIn('status', [3, 5])
                ->get();
        } else {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('receiver_warehouse_id', $warehouseUser->warehouse_id)
                ->whereIn('status', [3, 5])
                ->get();
        }

        $value_data = '';
        if (count($bookingParcel) > 0) {
            foreach ($bookingParcel as $value) {
                $value_data .= '
                    <tr style="text-align:center;">
                            <td><Input name="receive_parcel_id[]" class="parcel_item" type="checkbox" value="' . $value->id . '"></td>
                            <td>' . $value->parcel_code . '</td>
                            <td>' . $value->sender_phone . '</td>
                            <td>' . $value->receiver_branch->name . '</td>
                            <td>' . sprintf("%.2f", ($value->net_amount + $value->pickup_charge)) . '</td>
                            <td >' . strtoupper($value->delivery_type) . '</td>
                            <td ><a href="javascript:void(0)" title="Reject" id="rejectParcel_' . $value->id . '" class="rejectParcel text-danger" data-parcel_id="' . $value->id . '"><i class="fa fa-times"></i></a></td>
                        </tr>';
            }
            return response()->json($value_data);
        } else{
            $value_data = '<tr><td colspan="7" style="text-align: center;">Parcel Not Found!</td></tr>';
            return response()->json($value_data);
        }
        //return view('admin.booking_parcel.branchVehicleAssignParcelList', $data);
    }

    public function confirmWarehouseReceived(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'receive_vehicle_id'   => 'required',
                'total_assign_parcel'  => 'required',
            ], [
                'total_assign_parcel.required' => 'Please checked minimum 1 parcel for Received!',
            ]);

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'errors'  => $validator->errors(),
                ];
                return response()->json($response);
            }

            $warehouseUser = auth()->guard('warehouse')->user();

            $currdate_time = date("Y-m-d H:i:s");

            $data_parcel_update = [
                'status'                   => 4,
                'receiver_warehouse_id'    => $warehouseUser->warehouse_id,
                'receiver_warehouse_type'  => $warehouseUser->warehouse->type,
                'vehicle_warehouse_status' => 2,
                'note'                     => $request->get('note'),
                'updated_at'               => $currdate_time,
            ];

            $data_parcel_logs = [];
            $booking_ids      = [];

            if ($request->total_assign_parcel > 0) {
                foreach ($request->get('receive_parcel_id') as $k => $v_id) {
                    $booking_id    = $v_id;
                    $booking_ids[] = $booking_id;

                    $data_parcel_logs[] = [
                        'booking_id'               => $booking_id,
                        'vehicle_id'               => $request->get('receive_vehicle_id'),
                        'receiver_warehouse_id'    => $warehouseUser->warehouse_id,
                        'receiver_warehouse_type'  => $warehouseUser->warehouse->type,
                        'note'                     => $request->get('note'),
                        'status'                   => 4,
                        'vehicle_warehouse_status' => 2,
                        'created_at'               => $currdate_time,
                        'updated_at'               => $currdate_time,
                    ];
                }

            } else {
                $error = [
                    'parcel_item' => "You didn't select parcel, please try again",
                ];
                $response = [
                    'success' => false,
                    'errors'  => $error,
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try {
                $data_update = BookingParcel::whereIn('id', $booking_ids)->update($data_parcel_update);
                $data_create = BookingParcelLog::insert($data_parcel_logs);

                DB::commit();
                $response = [
                    'success' => true,
                    'errors'  => [],
                ];
                return response()->json($response);

            } catch (\Exception$ex) {
                DB::rollBack();
                $response = [
                    'success' => false,
                    'errors'  => [$ex->getMessage()],
                ];
                return response()->json($response);
            }
        }
    }

    public function rejectParcelFromWarehouse(Request $request) {

        if ($request->ajax()) {

            $admin_user_id  = auth()->guard('admin')->user()->id;
            $booking_id     = $request->get('booking_id');
            $booking_parcel = BookingParcel::find($booking_id);

            $booking_update = [
                'status'                   => 0,
                'receiver_warehouse_id'    => 0,
                'receiver_warehouse_type'  => NULL,
                'vehicle_warehouse_status' => 1,
                'updated_admin_user_id'    => $admin_user_id,
            ];

            $booking_parcel_log = [
                'booking_id'               => $booking_id,
                'vehicle_id'               => $booking_parcel->vehicle_id,
                'sender_warehouse_id'      => $booking_parcel->sender_warehouse_id,
                'sender_warehouse_type'    => $booking_parcel->sender_warehouse_type,
                'receiver_warehouse_id'    => $booking_parcel->receiver_warehouse_id,
                'receiver_warehouse_type'  => $booking_parcel->receiver_warehouse_type,
                'vehicle_warehouse_status' => $booking_parcel->vehicle_warehouse_status,
                'status'                   => 0,
                'created_admin_user_id'    => $admin_user_id,
                'updated_admin_user_id'    => $admin_user_id,
            ];

//            dd($booking_update, $booking_parcel_log);

            DB::beginTransaction();
            try {
                $update_parcel = $booking_parcel->update($booking_update);
                $log_save      = BookingParcelLog::create($booking_parcel_log);

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
//            return $ex->getMessage();
                return response()->json($response, 500);
            }

        }

    }

    public function getParcelListForWarehouseToVehicleWarehouseAssign(Request $request) {
        $warehouseUser = auth()->guard('warehouse')->user();

        if ($request->receiver_branch_id != "") {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('receiver_warehouse_id', $warehouseUser->warehouse_id)
                ->where('receiver_branch_id', $request->receiver_branch_id)
                ->whereIn('status', [0, 4])
                ->where('vehicle_warehouse_status', 2)
                ->get();
        } else {
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                ->where('receiver_warehouse_id', $warehouseUser->warehouse_id)
                ->whereIn('status', [0, 4])
                ->where('vehicle_warehouse_status', 2)
                ->get();
        }


        $value_data = '';
        if (count($bookingParcel) > 0) {
            foreach ($bookingParcel as $value) {
                $value_data .= '<tr style="text-align:center;">
                                <td><Input name="assign_parcel_id[]" class="assign_parcel_item" type="checkbox" value="' . $value->id . '"></td>
                                <td>' . $value->parcel_code . '</td>
                                <td>' . $value->sender_phone . '</td>
                                <td>' . $value->receiver_branch->name . '</td>
                                <td>' . sprintf("%.2f", ($value->net_amount + $value->pickup_charge)) . '</td>
                                <td >' . strtoupper($value->delivery_type) . '</td>
                            </tr>';
            }

            return response()->json($value_data);
        } else {
            $value_data = '<tr>
                               <td colspan="6" style="text-align: center;">Parcel Not Found!</td>
                            </tr>';
            return response()->json($value_data);
        }
    }

    public function confirmAssignVehicleOrWarehouse(Request $request) {
        // dd($request->all());
        $warehouseUser = auth()->guard('warehouse')->user();
        if ($request->ajax()) {
            if ($request->destination_branch == "yes") {
                $inputs_validation = [
                    'assign_vehicle_id'   => 'required',
                    'total_assign_parcel' => 'required',
                ];
            }
            else{
                $inputs_validation = [
                    'assign_vehicle_id'   => 'required',
                    'assign_warehouse_id' => 'required',
                    'total_assign_parcel' => 'required',
                ];
            }

            $validator = Validator::make($request->all(), $inputs_validation, [
                'total_assign_parcel.required' => 'Please checked minimum 1 parcel for Received!',
            ]);

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'errors'  => $validator->errors(),
                ];
                return response()->json($response);
            }

            $currdate_time = date("Y-m-d H:i:s");

            if ($request->destination_branch == "yes") {
                $data_parcel_update = [
                    'status'                   => 6,
                    'vehicle_id'               => $request->get('assign_vehicle_id'),
                    'vehicle_warehouse_status' => 1,
                    'note'                     => $request->get('note'),
                    'updated_at'               => $currdate_time,
                ];
            } else {
                $data_parcel_update = [
                    'status'                   => 5,
                    'vehicle_id'               => $request->get('assign_vehicle_id'),
                    'receiver_warehouse_id'    => $request->get('assign_warehouse_id'),
                    'receiver_warehouse_type'  => $request->get('assign_warehouse_type'),
                    'vehicle_warehouse_status' => 1,
                    'note'                     => $request->get('note'),
                    'updated_at'               => $currdate_time,
                ];
            }

            $data_parcel_logs = [];
            $booking_ids      = [];

            if ($request->total_assign_parcel > 0) {
                foreach ($request->get('assign_parcel_id') as $k => $v_id) {
                    $booking_id    = $v_id;
                    $booking_ids[] = $booking_id;
                    if ($request->get('destination_branch') == 'yes') {
                        $data_parcel_logs[] = [
                            'booking_id'               => $booking_id,
                            'vehicle_id'               => $request->get('assign_vehicle_id'),
                            'sender_warehouse_id'      => $warehouseUser->warehouse_id,
                            'sender_warehouse_type'    => $warehouseUser->warehouse->type,
                            'vehicle_warehouse_status' => 1,
                            'note'                     => $request->get('note'),
                            'status'                   => 6,
                            'created_at'               => $currdate_time,
                            'updated_at'               => $currdate_time,
                        ];
                    } else {
                        $data_parcel_logs[] = [
                            'booking_id'               => $booking_id,
                            'vehicle_id'               => $request->get('assign_vehicle_id'),
                            'sender_warehouse_id'      => $warehouseUser->warehouse_id,
                            'sender_warehouse_type'    => $warehouseUser->warehouse->type,
                            'receiver_warehouse_id'    => $request->get('assign_warehouse_id'),
                            'receiver_warehouse_type'  => $request->get('assign_warehouse_type'),
                            'vehicle_warehouse_status' => 1,
                            'note'                     => $request->get('note'),
                            'status'                   => 5,
                            'created_at'               => $currdate_time,
                            'updated_at'               => $currdate_time,
                        ];
                    }
                }
            } else {
                $error = [
                    'parcel_item' => "You did't select parcel, please try again",
                ];
                $response = [
                    'success' => false,
                    'errors'  => $error,
                ];
                return response()->json($response);
            }
            DB::beginTransaction();
            try {
                $data_update = BookingParcel::whereIn('id', $booking_ids)->update($data_parcel_update);
                $data_create = BookingParcelLog::insert($data_parcel_logs);

                DB::commit();
                $response = [
                    'success' => true,
                    'errors'  => [],
                ];
                return response()->json($response);

            } catch (\Exception$ex) {
                DB::rollBack();
                $response = [
                    'success' => false,
                    'errors'  => [$ex->getMessage()],
                ];
                return response()->json($response);
            }

        }
    }

}

<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
//use App\Http\Middleware\Merchant;
use App\Models\BookingItem;
use App\Models\BookingParcel;
use App\Models\BookingParcelPaymentDetails;
use App\Models\Rider;
use App\Models\Merchant;
use App\Models\Branch;
use App\Models\Area;
use App\Models\BookingParcelLog;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\ItemCategory;
use App\Models\ItemTbl;
use App\Models\Unit;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Cart;
use Darryldecode\Cart\Cart as CartCart;
use Yajra\DataTables\DataTables;

class parcelDestinationBranchController extends Controller{

    private $bookingParcelObj;
    public function __construct()
    {
        $this->bookingParcelObj    = new BookingParcel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function bookingParcelReceiveList(){
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcelReceiveList';
        $data['page_title'] = 'Booking Parcel Receive List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.booking_parcel.bookingParcelReceiveList', $data);
    }

    public function getBookingParcelReceiveList() {
        $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses'])
                ->where('receiver_branch_id', $this->branchId())
                ->whereIn('status', [6,7,8,9])->select()->get();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('net_amount', function ($data) {
                $total_amount = $data->net_amount + $data->pickup_charge;
                return sprintf("%.2f", $total_amount);
            })
            ->editColumn('booking_parcel_type', function ($data) {
                switch ($data->booking_parcel_type) {
                    case 'cash':$booking_type  = "Cash"; $class  = "success";break;
                    case 'to_pay':$booking_type = "To Pay"; $class = "info";break;
                    case 'condition':$booking_type  = "Condition"; $class  = "primary";break;
                    case 'credit':$booking_type = "Credit"; $class = "warning";break;
                    default:$booking_type    = "None"; $class    = "danger";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $booking_type . '</a>';
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
                $receiver_warehouse_name = ($data->receiver_warehouses) ? $data->receiver_warehouses->wh_name : 'Warehouse';

                switch ($data->status) {
                    case 0:$status_name    = "Parcel Reject from operation"; $class  = "danger";break;
                    case 1:$status_name    = "Confirmed Booking"; $class  = "success";break;
                    case 2:$status_name    = "Vehicle Assigned"; $class   = "success";break;
                    case 3:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 4:$status_name    = "Warehouse Received Parcel"; $class  = "success";break;
                    case 5:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 6:$status_name    = "On the way to receive"; $class  = "success";break;
                    case 7:$status_name    = "Received Parcel"; $class  = "success";break;
                    case 8:$status_name    = "Parcel Delivery Complete"; $class  = "success";break;
                    case 9:$status_name    = "Parcel Delivery Return"; $class  = "success";break;
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
                $button = '<a href="#" class="btn btn-success btn-sm" title="Print Booking Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                // $button .= '&nbsp; <button class="btn btn-info btn-sm float-right print-modal" title="Print Barcode" data-toggle="modal" data-target="#printBarcode" booking_id="' . $data->id . '">
                //     <i class="fas fa-barcode"></i>
                // </button>';
                if($data->status == 7){
                    $button .= '&nbsp; <button class="btn btn-success btn-sm delivery-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
                    <i class="fas fa-check"></i> </button>';
                }

                return $button;
            })
            ->rawColumns(['booking_parcel_type', 'delivery_type', 'status', 'action'])
            ->make(true);
    }

    public function printBookingParcelReceiveList() {
        $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses'])
                ->where('receiver_branch_id', $this->branchId())
                ->whereIn('status', [6,7,8,9])->select()->get();
        $bookingParcels = $model;
        return view('branch.booking_parcel.printBookingParcelReceiveList', compact('bookingParcels'));
    }

    public function receiveBookingParcel(){
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'receiveBookingParcel';
        $data['page_title'] = 'Booking Parcel Receive';
        $data['collapse']   = 'sidebar-collapse';

        $data['branches']   = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['vehicles']   = Vehicle::where([
            ['status', '=', 1],
        ])->get();
        return view('branch.booking_parcel.bookingParcelReceive', $data);
    }

    public function getParcelListForDestinationBranchReceive(Request $request){

        if($request->booking_branch_id != 'all'){
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                                    ->where('vehicle_id', $request->vehicle_id)
                                    ->where('sender_branch_id', $request->booking_branch_id)
                                    ->where('receiver_branch_id', $this->branchId())
                                    ->whereIn('status', array(6))
                                    ->get();
        }else{
            $bookingParcel = BookingParcel::with(['receiver_branch'])
                                    ->where('vehicle_id', $request->vehicle_id)
                                    ->where('receiver_branch_id', $this->branchId())
                                    ->whereIn('status', array(6))
                                    ->get();
        }

        $value_data = '';
        foreach($bookingParcel as $value){
            $value_data .= '<tr style="text-align:center;">
                            <td><Input name="parcel_id[]" class="parcel_item" type="checkbox" value="'.$value->id.'"></td>
                            <td>'.$value->parcel_code.'</td>
                            <td>'.$value->sender_phone.'</td>
                            <td>'.$value->receiver_branch->name.'</td>
                            <td>'.sprintf("%.2f", ($value->net_amount+$value->pickup_charge)).'</td>
                            <td >'.strtoupper($value->delivery_type).'</td>
                            <td ><a href="javascript:void(0)" title="Reject" id="rejectParcel_' . $value->id . '"  class="rejectParcel text-danger" data-parcel_id="'.$value->id.'"><i class="fa fa-times"></i></a></td>
                        </tr>';
        }
        return response()->json($value_data);
    }

    public function confirmDestinationReceivedParcel(Request $request)
    {
        if($request->ajax()) {

//            dd($request->all());
            $validator = Validator::make($request->all(), [
                'vehicle_id'               => 'required',
                'total_receive_parcel'             => 'required',
            ], [
                'total_receive_parcel.required' => 'Please checked minimum 1 parcel for Received!',
            ]);

            if ($validator->fails()) {

                $response   = [
                    'success'   => false,
                    'errors'    => $validator->errors()
                ];
                return response()->json($response);
            }

            $user_id = $this->userId();
            $currdate_time  = date("Y-m-d H:i:s");


            $data_parcel_update    = [
                'status'                    => 7,
                'vehicle_id'                => 0,
                'sender_warehouse_id'       => 0,
                'sender_warehouse_type'     => NULL,
                'receiver_warehouse_id'     => 0,
                'receiver_warehouse_type'   => NULL,
                'vehicle_warehouse_status'  => 0,
                'note'                      => $request->get('note'),
                'updated_branch_user_id'    => $user_id,
                'updated_at'                => $currdate_time,

            ];

            $data_parcel_logs   = [];
            $booking_ids = [];
            if($request->total_receive_parcel > 0) {

                foreach ($request->get('parcel_id') as $k=>$v_id) {
                    $booking_id = $v_id;
                    $parcel_data    = BookingParcel::where('id', $booking_id)->select('sender_warehouse_id', 'sender_warehouse_type')->first();
                    $booking_ids[]  = $booking_id;

                    $data_parcel_logs[] = [
                        'booking_id'                    => $booking_id,
                        'vehicle_id'                    => $request->get('vehicle_id'),
                        'sender_warehouse_id'           => $parcel_data->sender_warehouse_id,
                        'sender_warehouse_type'         => $parcel_data->sender_warehouse_type,
                        'note'                          => $request->get('note'),
                        'status'                        => 7,
                        'vehicle_warehouse_status'      => 0,
                        'created_branch_user_id'        => $user_id,
                        'updated_branch_user_id'        => $user_id,
                        'created_at'                    => $currdate_time,
                        'updated_at'                    => $currdate_time,
                    ];
                }
            }else{
                $error = array(
                    'parcel_item' => "You did't select parcel, please try again"
                );
                $response   = [
                    'success'   => false,
                    'errors'    => $error
                ];
                return response()->json($response);
            }

//            dd($data_parcel_update, $data_parcel_logs, $booking_ids);
            DB::beginTransaction();
            try{
                $data_update    = BookingParcel::whereIn('id', $booking_ids)->update($data_parcel_update);
                $data_create    = BookingParcelLog::insert($data_parcel_logs);

                DB::commit();
                $response   = [
                    'success'   => true,
                    'errors'    => []
                ];
                return response()->json($response);

            }catch (\Exception $ex) {
                DB::rollBack();
                $response   = [
                    'success'   => false,
                    'errors'    => [$ex->getMessage()]
                ];
                return response()->json($response);
            }


        }
    }


    public function rejectParcelFromDestination(Request $request) {

        if($request->ajax()) {

            $user_id  = $this->userId();
            $booking_id = $request->get('booking_id');
            $booking_parcel = BookingParcel::find($booking_id);
            $curr_date_time = date("Y-m-d H:i:s");

            $booking_update = [
                'status'    => 0,
                'updated_branch_user_id' => $user_id,
                'updated_at' => $curr_date_time,
            ];

            $booking_parcel_log = [
                'booking_id'                => $booking_id,
                'vehicle_id'                => $booking_parcel->vehicle_id,
                'sender_warehouse_id'       => $booking_parcel->sender_warehouse_id,
                'sender_warehouse_type'     => $booking_parcel->sender_warehouse_type,
                'receiver_warehouse_id'     => $booking_parcel->receiver_warehouse_id,
                'receiver_warehouse_type'   => $booking_parcel->receiver_warehouse_type,
                'vehicle_warehouse_status'  => $booking_parcel->vehicle_warehouse_status,
                'status'                    => 0,
                'created_branch_user_id'    => $user_id,
                'updated_branch_user_id'    => $user_id,
                'created_at'                => $curr_date_time,
                'updated_at'                => $curr_date_time,
            ];

            //dd($booking_update, $booking_parcel_log);

            DB::beginTransaction();
            try {
                $update_parcel  = $booking_parcel->update($booking_update);
                $log_save       = BookingParcelLog::create($booking_parcel_log);

                DB::commit();
                $response   = [
                    'success'   => true,
                    'errors'    => []
                ];
                return response()->json($response);

            } catch (\Exception $ex) {
                DB::rollback();
                $response   = [
                    'success'   => false,
                    'errors'    => [$ex->getMessage()]
                ];
//            return $ex->getMessage();
                return response()->json($response, 500);
            }
        }
    }


    public function deliveryBookingParcel(Request $request, BookingParcel $booking_parcel) {
        $booking_parcel->load(['sender_branch' => function ($query) {$query->select('id', 'name');},
            'receiver_branch'                      => function ($query) {$query->select('id', 'name');}, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items',
        ]);
        return view('branch.booking_parcel.deliveryBookingParcel', compact('booking_parcel'));
    }


    public function confirmDeliveryBookingParcel(Request $request, BookingParcel $booking_parcel) {
        // dd($request->all());
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'delivery_type'                 => 'required',
                'customer_collect_amount'       => 'sometimes',
                'customer_due_amount'           => 'sometimes',
                'booking_parcel_note'           => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $check = BookingParcel::where([
                            'status'    => 7,
                        ])->first();


                    if ($check) {
                        $delivery_type              = $request->delivery_type;
                        $customer_due_amount        = $request->customer_due_amount;
                        $due_amount                 = $request->due_amount;
                        $customer_collected_amount  = $request->customer_collected_amount;
                        $collection_amount          = $request->collection_amount;
                        $booking_parcel_note        = $request->booking_parcel_note;
                        $booking_parcel_type        = $request->booking_parcel_type;
                        $branch                     = auth()->guard('branch')->user();

                        $currdate_time          = date("Y-m-d H:i:s");
                        if($delivery_type == 0  || is_null($delivery_type) || !($delivery_type == 1 || $delivery_type == 2)){
                            return response()->json([
                                'error' => "Delivery Type required",
                            ]);
                        }

                        if($delivery_type == 1){
                            if($booking_parcel->due_amount != $customer_due_amount){
                                return response()->json([
                                    'success' => 401,
                                    'message' => "Booking Parcel Due not Matching",
                                ], 401);
                            }

                            if($booking_parcel->booking_parcel_type == 'condition' && $booking_parcel->collection_amount != $customer_collected_amount){
                                return response()->json([
                                    'success' => 401,
                                    'message' => "Booking Parcel Collection not Matching",
                                ], 401);
                            }

                            $booking_parcel_data = [
                                'status'                    => 8,
                                'customer_due_amount'       => $customer_due_amount,
                                'customer_collected_amount' => $customer_collected_amount,
                                'note'                      => $booking_parcel_note,
                                // 'updated_at'        => $currdate_time,
                            ];
                            $booking_parcel_log_data = [
                                'booking_id'        => $booking_parcel->id,
                                'status'            => 8,
                                'note'              => $booking_parcel_note,
                                'created_branch_user_id'  => $branch->id,
                                // 'created_at'        => $currdate_time,
                                // 'updated_at'        => $currdate_time,
                            ];

                            $booking_parcel_payment_data = [];
                            if("general" == $booking_parcel->booking_parcel_type || "to_pay" == $booking_parcel->booking_parcel_type){
                                $booking_parcel_payment_data = [
                                    'booking_id'            => $booking_parcel->id,
                                    'payment_receive_type'  => 'delivery',
                                    'delivery_charge'       => $customer_due_amount,
                                    'total_amount'          => $customer_due_amount,
                                    'branch_id'             => $this->branchId(),
                                    'created_branch_user_id'=> $this->userId(),
                                    'payment_date'          => date("Y-m-d"),
                                ];
                            }elseif ("condition" == $booking_parcel->booking_parcel_type) {
                                $booking_parcel_payment_data = [
                                    'booking_id'            => $booking_parcel->id,
                                    'payment_receive_type'  => 'delivery',
                                    'collection_amount'     => $customer_collected_amount,
                                    'cod_charge'            => number_format((float)($customer_collected_amount / 100) * 1, 4, '.', ''),
                                    'delivery_charge'       => $customer_due_amount,
                                    'total_amount'          => $customer_collected_amount,
                                    'branch_id'             => $this->branchId(),
                                    'created_branch_user_id'=> $this->userId(),
                                    'payment_date'          => date("Y-m-d"),
                                ];
                            }
                        }

                        if($delivery_type == 2){
                            $booking_parcel_data = [
                                'status'            => 9,
                                // 'collection_amount' => $customer_collect_amount,
                                'note'              => $booking_parcel_note,
                                // 'updated_at'        => $currdate_time,
                            ];
                            $booking_parcel_log_data = [
                                'booking_id'        => $booking_parcel->id,
                                'status'            => 9,
                                'note'              => $booking_parcel_note,
                                'created_branch_user_id'  => $branch->id,
                                // 'created_at'        => $currdate_time,
                                // 'updated_at'        => $currdate_time,
                            ];
                        }

                        $booking_parcel->update($booking_parcel_data);
                        BookingParcelLog::create($booking_parcel_log_data);
                        if($delivery_type == 1 && $booking_parcel_payment_data > 0) {
                            BookingParcelPaymentDetails::create($booking_parcel_payment_data);
                        }

                        \DB::commit();
                        $response = ['success' => 'Booking Parcel Delivery Complete Successfully'];
                    }
                    else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found' ];
                }
            }
        }
        return response()->json($response);
    }




    /** Protected Function */
    protected function userId()
    {
        if(auth()->guard('admin')->user()) {
            $userId = auth()->guard('admin')->user()->id;
        }else{
            $userId = auth()->guard('branch')->user()->id;
        }

        return $userId;
    }

    protected function branchId()
    {
        if(auth()->guard('branch')->user()) {
            $branchId = auth()->guard('branch')->user()->branch_id;
        }else{
            $branchId = "";
        }

        return $branchId;
    }



}

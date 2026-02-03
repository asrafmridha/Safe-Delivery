<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\BookingParcel;
use App\Models\BookingParcelLog;
use App\Models\Vehicle;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingVehicleController extends Controller {

    public function assignVehicle() {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        Cart::session($branch_id)->clear();

        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'assignVehicle';
        $data['page_title'] = 'Booking Parcel Assign Vehicle';
        $data['collapse']   = 'sidebar-collapse';
        $data['vehicles']   = Vehicle::where([
            'status' => 1,
        ])->get();

        $data['receiver_branches'] = BookingParcel::with(['receiver_branch' => function ($query) {
            $query->select('id', 'name');
        }])->whereIn('status', [0, 1])
            ->select('receiver_branch_id')
            ->distinct()
            ->get();

        $data['bookingParcel'] = BookingParcel::where([
            'sender_branch_id' => $branch_id,
        ])
            ->whereIn('status', [0, 1])
            ->get();

        return view('branch.booking_parcel.bookingVehicleAssign', $data);
    }

    public function confirmAssignVehicleBookingParcel(Request $request) {

        $validator = Validator::make($request->all(), [
            'total_assign_parcel' => 'required',
            'vehicle_id'          => 'required',
            'date'                => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user_id       = auth()->guard('branch')->user()->id;
        $branch_id     = auth()->guard('branch')->user()->branch_id;
        $assign_date   = $request->get('date');
        $currdate_time = date("Y-m-d", strtotime($request->get('date'))) . " " . date("H:i:s");

        $data_parcel = [
            'status'                   => 2,
            'vehicle_warehouse_status' => 1,
            'vehicle_id'               => $request->input('vehicle_id'),
            'note'                     => $request->get('note'),
            'updated_branch_user_id'   => $user_id,
            'updated_at'               => $currdate_time,

        ];

        $data_parcel_logs = [];
        $booking_ids      = [];

        if ($request->total_assign_parcel > 0) {
            $cart = Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($cart as $item) {
                $booking_id = $item->id;

                $booking_ids[] = $booking_id;

                $data_parcel_logs[] = [
                    'booking_id'               => $booking_id,
                    'vehicle_id'               => $request->get('vehicle_id'),
                    'note'                     => $request->get('note'),
                    'status'                   => 2,
                    'vehicle_warehouse_status' => 1,
                    'created_branch_user_id'   => $branch_id,
                    'updated_branch_user_id'   => $branch_id,
                    'created_at'               => $currdate_time,
                    'updated_at'               => $currdate_time,
                ];
            }

        }

        //dd($data_parcel, $data_parcel_logs, $booking_ids);

        DB::beginTransaction();
        try {
            $parcel_update   = BookingParcel::whereIn('id', $booking_ids)->update($data_parcel);
            $parcel_log_save = BookingParcelLog::insert($data_parcel_logs);

            DB::commit();
            $this->setMessage('Booking parcel vehicle assign Successfully', 'success');
            return redirect()->back();

        } catch (\Exception$ex) {
            DB::rollback();
            $this->setMessage('Booking parcel vehicle assign Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

//    public function confirmAssignVehicleBookingParcel(Request $request) {

//

//        $validator = Validator::make($request->all(), [

//            'total_assign_parcel' => 'required',

//            'vehicle_id'          => 'required',

//            'date'                => 'required',

//        ]);

//

//        if ($validator->fails()) {

//            return redirect()->back()->withInput()->withErrors($validator);

//        }

//

//        $branch_id = auth()->guard('branch')->user()->id;

//        $assign_date    = $request->get('date');

//        $currdate_time  = date("Y-m-d", strtotime($request->get('date')))." ".date("H:i:s");

//

//

//        $checkAssignVehicleExists = BookingVehicle::where('vehicle_id',$request->get('vehicle_id'))

//                                                ->where('branch_id', $branch_id)

//                                                ->where('assign_date', $assign_date)

//                                                ->first();

//

//        $data_vehicle_master = [

//            'vehicle_id'         => $request->input('vehicle_id'),

//            'branch_id'          => $branch_id,

//            'assign_date'        => $assign_date,

//            'created_at'         => $currdate_time,

//            'updated_at'         => $currdate_time,

//        ];

//

//        $data_vehicle_master_update = [

//            'updated_at'         => $currdate_time,

//        ];

//

//        $data_parcel    = [

//            'status'                    => 2,

//            'note'                      => $request->get('note'),

//            'updated_branch_user_id'    => $branch_id,

//            'updated_at'                => $currdate_time,

//

//        ];

//

//        $data_vehicle_plist   = [];

//        $data_parcel_logs   = [];

//        $booking_ids = [];

//        if($request->total_assign_parcel > 0) {

//            $cart = Cart::session($branch_id)->getContent();

//            $cart = $cart->sortBy('id');

//

//            foreach ($cart as $item) {

//                $booking_id = $item->id;

//

//                $booking_ids[]  = $booking_id;

//                $data_vehicle_plist[]  = new BookingVehiclePlist([

//                    'booking_id'    => $booking_id,

//                    'created_at'    => $currdate_time,

//                    'updated_at'    => $currdate_time,

//                ]);

//

//                $data_parcel_logs[] = [

//                    'booking_id'    => $booking_id,

//                    'branch_vehicle_id' => $request->get('vehicle_id'),

//                    'note'          => $request->get('note'),

//                    'status'        => 2,

//                    'created_branch_user_id' => $branch_id,

//                    'updated_branch_user_id' => $branch_id,

//                    'created_at' => $currdate_time,

//                    'updated_at' => $currdate_time,

//                ];

//            }

//        }

//

////        dd($data_parcel, $data_parcel_logs, $data_vehicle_master, $data_vehicle_plist, $booking_ids);

//

//        DB::beginTransaction();

//        try {

//            $parcel_update    = BookingParcel::whereIn('id',$booking_ids)->update($data_parcel);

//            $parcel_log_save  = BookingParcelLog::insert($data_parcel_logs);

//

//            if(!empty($checkAssignVehicleExists)){

//                $vehicle_save     = $checkAssignVehicleExists->update($data_vehicle_master_update);

//                $vehicle_plist_save = $checkAssignVehicleExists->booking_vehicle_plists()->saveMany($data_vehicle_plist);

//            }else{

//                $vehicle_save     = BookingVehicle::create($data_vehicle_master);

//                $vehicle_plist_save = $vehicle_save->booking_vehicle_plists()->saveMany($data_vehicle_plist);

//            }

//

//

//            DB::commit();

//            $this->setMessage('Booking parcel vehicle assign Successfully', 'success');

//            return redirect()->back();

//

//        } catch (\Exception $ex) {

//            DB::rollback();

//            $this->setMessage('Booking parcel vehicle assign Failed', 'danger');

//            return redirect()->back()->withInput();

//        }

//
//    }

}

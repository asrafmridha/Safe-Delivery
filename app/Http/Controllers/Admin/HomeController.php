<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingParcel;
use App\Models\BookingParcelPayment;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Mail\AdminPasswordRestMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller{


    public function home() {
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Home';

        $counter_data   = parent::returnDashboardCounterForAdmin();

        $data['counter_data'] = $counter_data;

        //dd($counter_data);

//        $data['branches']       = Branch::select('id')->get();
//        $data['riders']         = Rider::select('id')->get();
//        $data['merchants']      = Merchant::select('id')->get();
//        $data['warehouses']     = Warehouse::select('id')->get();
//
//        /** E-courier */
//
//        $data['todayPickupRequest']     = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (1,4,5,6,7,8,10,9,11)', [date("Y-m-d")])->select('id')->get();
//        $data['todayPickupComplete']    = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (11)', [date("Y-m-d")])->select('id')->get();
//        $data['todayPickupPending']     = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (1,4,5,6,7,8,10)', [date("Y-m-d")])->select('id')->get();
//        $data['todayPickupCancel']      = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (9)', [date("Y-m-d")])->select('id')->get();
//
//        $data['etodayDeliveryParcels']      = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status in (14,16,17,18,19,20,21,22,23,24,25)', [date("Y-m-d")])->select('id')->get();
//        $data['etodayDeliveryComplete']     = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status in (25)', [date("Y-m-d")])->select('id')->get();
//        $data['etodayDeliveryPending']      = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and reschedule_parcel_date > ? and status in (23)', [date("Y-m-d"), date("Y-m-d")])->select('id')->get();
//        $data['etodayDeliveryCancel']       = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status in (20)', [date("Y-m-d")])->select('id')->get();
//
//        $data['etotalDeliveryParcels']      = Parcel::whereRaw('delivery_branch_id != "" and status in (14,16,17,18,19,20,21,22,23,24,25)')->select('id')->get();
//        $data['etotalDeliveryComplete']     = Parcel::whereRaw('delivery_branch_id != "" and status in (25)')->select('id')->get();
//        $data['etotalDeliveryPending']      = Parcel::whereRaw('delivery_branch_id != "" and reschedule_parcel_date > ? and status in (23)', [date("Y-m-d")])->select('id')->get();
//        $data['etotalDeliveryCancel']       = Parcel::whereRaw('delivery_branch_id != "" and status in (20)')->select('id')->get();
//
//        $total_ecourier_collection              = Parcel::whereRaw('delivery_branch_id != "" and delivery_type in (1,2)')->sum('customer_collect_amount');
//        $data['ecourierTotalCollectAmount']     = number_format((float) ($total_ecourier_collection), 2, '.', '');
//
//        $ecourier_collection_paid_to_account    = Parcel::whereRaw('delivery_branch_id != "" and delivery_type in (1,2) and payment_type in(2, 4, 5, 6)')->sum('customer_collect_amount');
//        $data['ecourierPaidToAccount']          = number_format((float) $ecourier_collection_paid_to_account, 2, '.', '');
//
//        $data['ecourierBalanceCollectAmount']   = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_account), 2, '.', '');
//
//
//        /** Traditional */
//        $data['totalDeliveryParcels']       = BookingParcel::whereRaw('status in (8)')->select('id')->get();
//        $data['todayDeliveryParcels']       = BookingParcel::whereRaw('DATE(updated_at) = ? and status in (8)', [date("Y-m-d")] )->select('id')->get();
//
//        $customer_collection_amount     = BookingParcel::whereRaw('status in (8)')->sum('customer_collected_amount');
//        $customer_due_amount            = BookingParcel::whereRaw('status in (8)')->sum('customer_due_amount');
//        $total_delivery_collection      = $customer_collection_amount + $customer_due_amount;
//        $data['totalDeliveryCollectionAmount']  = number_format( (float) $total_delivery_collection, 2, '.', '');
//
//        $data['totalBookingParcels']        = BookingParcel::select('id')->get();
//        $data['todayBookingParcels']        = BookingParcel::whereRaw('booking_date = ?', [date("Y-m-d")] )->select('id')->get();
//
//        $total_booking_collection       = BookingParcel::sum('paid_amount');
//        $data['totalBookingParcelsCollectAmount']   = number_format((float) $total_booking_collection, 2, '.', '');
//        $data['totalCollectAmount']     = number_format((float) ($total_delivery_collection + $total_booking_collection), 2, '.', '');
//
//        $data['totalRejectParcels']     = BookingParcel::whereRaw('status in (0)')->select('id')->get();
//        $data['todayRejectParcels']     = BookingParcel::whereRaw('DATE(updated_at) = ? and status in (0)', [date("Y-m-d")] )->select('id')->get();
//
//        $collection_accounts_amount         = BookingParcelPayment::whereRaw('payment_status = ?', [2] )->sum('receive_amount');
//        $data['accountsTotalBalance']       = number_format((float) $collection_accounts_amount, 2, '.', '');

//        $data['balanceCollectAmount'] = number_format((float) ($data['totalCollectAmount'] - $collection_paid_to_account), 2, '.', '');


        return view('admin.home', $data);
    }


    public function profile() {
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Profile';
        $data['admin_user'] = Admin::where('id', auth()->guard('admin')->user()->id)->first();
        return view('admin.account.profile', $data);
    }


}

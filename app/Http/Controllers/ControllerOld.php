<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardRealTimeCounter;
use App\Events\BranchDashboardRealTimeCounter;
use App\Events\MerchantDashboardRealTimeCounter;
use App\Models\BookingParcelPayment;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\DeliveryBranchTransfer;
use App\Models\Merchant;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Parcel;
use App\Models\BookingParcel;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ReturnBranchTransfer;
use App\Models\WeightPackage;
use Intervention\Image\Facades\Image;

class ControllerOld extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function setMessage($message, $type){
        session()->flash('message', $message);
		session()->flash('type', $type);
    }

    function sweetAlertMessage($type, $message, $title=''){
        session()->flash('alert-title', $title);
        session()->flash('alert-message', $message);
		session()->flash('alert-type', $type);
    }


    public function send_sms($phone,$message) {
        // http://smpp.ajuratech.com:7788/sendtext?apikey=API_KEY&secretkey=SECRET_KEY
        // &callerID=SENDER_ID&toUser=MOBILE_NUMBER&messageContent=MESSAGE
        // $url = "http://smpp.ajuratech.com:7788/sendtext";
        // $data = [
        //     "apikey"            => "759aa2482a1fceb0",
        //     "secretkey"         => "ef4133bb",
        //     "callerID"          => "BEACON  COURIER",
        //     "type"              => "text",
        //     "toUser"            => $phone,
        //     "messageContent"    => $message,
        // ];

//        $apiKey     = "759aa2482a1fceb0";
//        $secretkey  = "ef4133bb";
//        $callerID   = urlencode("BEACON  COURIER");
        $username   = "sms@beaconcourier.com.bd";
        $password   = "123456";
        $from       = urlencode("8809612440273");
        $phone      = urlencode('880' . substr(preg_replace('/\D/', '', $phone), -10));
        $message    = urlencode($message);

//        $url    = "http://smpp.ajuratech.com:7788/sendtext";
//        $url    .= "?apikey=".$apiKey;
//        $url    .= "&secretkey=".$secretkey;
//        $url    .= "&callerID=".$callerID;
//        $url    .= "&toUser=".$phone;
//        $url    .= "&messageContent=".$message;

        $url    = "https://papi.dreamlineit.com/api/v1/channels/sms";
        $url    .= "?username=".$username;
        $url    .= "&password=".$password;
        $url    .= "&recipient=".$phone;
        $url    .= "&from=".$from;
        $url    .= "&message=".$message;
//        return $url;

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        return $response;
    }


    function returnUniqueParcelInvoice(){
        $lastParcel = Parcel::orderBy('id', 'desc')->first();

        $currentDate = date("Ymd");
        if(!empty($lastParcel)){
            $get_serial = explode("-", $lastParcel->parcel_invoice);
            $current_serials = $get_serial[1] +1;
            $parcel_invoice = $currentDate.'-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $parcel_invoice = $currentDate.'-00001';
        }
        return $parcel_invoice;
    }

    function returnUniqueBookingParcelInvoice($sdname, $rdname){
        $lastParcel = BookingParcel::orderBy('id', 'desc')->first();

        $sdisname = strtoupper(substr($sdname,0,2));
        $rdisname = strtoupper(substr($rdname,0,2));
        $currentDate = date("Ymd");

        $strcode    = 'METTRO'.$sdisname.$rdisname.$currentDate;
        if(!empty($lastParcel)){
            $get_serial = explode("-", $lastParcel->parcel_code);
            $current_serials = $get_serial[1] +1;
            $parcel_invoice = $strcode.'-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $parcel_invoice = $strcode.'-00001';
        }
        return $parcel_invoice;
    }

    function returnUniqueRiderRunInvoice(){
        $lastRiderRun = RiderRun::orderBy('id', 'desc')->first();

        if(!empty($lastRiderRun)){
            $get_serial = explode("-", $lastRiderRun->run_invoice);
            $current_serials = $get_serial[1] +1;
            $run_invoice = 'RUN-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $run_invoice = 'RUN-00001';
        }
        return $run_invoice;
    }

    function returnUniqueDeliveryPaymentInvoice(){
        $lastDeliveryPayment = ParcelDeliveryPayment::orderBy('id', 'desc')->first();

        if(!empty($lastDeliveryPayment)){
            $get_serial = explode("-", $lastDeliveryPayment->payment_invoice);
            $current_serials = $get_serial[1] +1;
            $payment_invoice = 'PAY-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $payment_invoice = 'PAY-00001';
        }
        return $payment_invoice;
    }

    function returnUniqueParcelPaymentBillNo(){
        $lastParcelPayment = BookingParcelPayment::orderBy('id', 'desc')->first();

        if(!empty($lastParcelPayment)){
            $get_serial = explode("-", $lastParcelPayment->bill_no);
            $current_serials = $get_serial[1] +1;
            $payment_invoice = 'PAY-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $payment_invoice = 'PAY-00001';
        }
        return $payment_invoice;
    }

    function returnUniqueMerchantDeliveryPaymentInvoice(){
        $lastDeliveryPayment = ParcelMerchantDeliveryPayment::orderBy('id', 'desc')->first();

        if(!empty($lastDeliveryPayment)){
            $get_serial = explode("-", $lastDeliveryPayment->merchant_payment_invoice);
            $current_serials = $get_serial[1] +1;
            $merchant_payment_invoice = 'MPAY-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $merchant_payment_invoice = 'MPAY-00001';
        }
        return $merchant_payment_invoice;
    }

    function returnUniqueMerchantId(){
        $merchant = Merchant::orderBy('id', 'desc')->first();

        if(!empty($merchant)){
            $get_serial = explode("-", $merchant->m_id);
            $current_serials = $get_serial[1] +1;
            $m_id = 'M-'.str_pad($current_serials, 4, '0', STR_PAD_LEFT);
        }
        else{
            $m_id = 'M-0001';
        }
        return $m_id;
    }

    function returnUniqueRiderId(){
        $rider = Rider::orderBy('id', 'desc')->first();
        if(!empty($rider)){
            $get_serial = explode("-", $rider->r_id);
            $current_serials = $get_serial[1] +1;
            $r_id = 'R-'.str_pad($current_serials, 4, '0', STR_PAD_LEFT);
        }
        else{
            $r_id = 'R-0001';
        }
        return $r_id;
    }

    function returnUniqueBranchTransferInvoice(){
        $lastDeliveryBranchTransfer = DeliveryBranchTransfer::orderBy('id', 'desc')->first();

        if(!empty($lastDeliveryBranchTransfer)){
            $get_serial = explode("-", $lastDeliveryBranchTransfer->delivery_transfer_invoice);
            $current_serials = $get_serial[1] +1;
            $invoice = 'Transfer-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $invoice = 'Transfer-00001';
        }
        return $invoice;
    }

    function returnUniqueReturnTransferInvoice(){
        $lastDeliveryBranchTransfer = ReturnBranchTransfer::orderBy('id', 'desc')->first();

        if(!empty($lastReturnBranchTransfer)){
            $get_serial = explode("-", $lastReturnBranchTransfer->return_transfer_invoice);
            $current_serials = $get_serial[1] +1;
            $invoice = 'RTransfer-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $invoice = 'RTransfer-00001';
        }
        return $invoice;
    }

    function returnUniqueWeightPackageID(){
        $weightPackage = WeightPackage::orderBy('id', 'desc')->first();
        if(!empty($weightPackage)){
            $get_serial = explode("-", $weightPackage->wp_id);
            $current_serials = $get_serial[1] +1;
            $wp_id = 'WP-'.str_pad($current_serials, 2, '0', STR_PAD_LEFT);
        }
        else{
            $wp_id = 'WP-01';
        }
        return $wp_id;
    }


    public  function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function uploadFile($image, $upload_path){
        $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
        $image_path = 'public/uploads/'.$upload_path.'/'. $image_name;
        Image::make($image)->save($image_path);
        return $image_path;
    }


    /** For Dashboard Counter */

    protected function returnDashboardCounterForAdmin()
    {
        $data = array();

        $data['branches'] = Branch::select('id')->count();
        $data['branch_url'] = route('admin.branch.index');
        $data['riders'] = Rider::select('id')->count();
        $data['rider_url'] = route('admin.rider.index');
        $data['merchants'] = Merchant::select('id')->count();
        $data['merchant_url'] = route('admin.merchant.index');
        $data['warehouses'] = Warehouse::select('id')->count();
        $data['warehouse_url'] = route('admin.warehouse.index');


//        $admin_user = auth()->guard('admin')->user();
//        $admin_type = $admin_user->type;
//        if($admin_type == 3) {
            /** 1st Row */
            // Today Product Value = Today Total collect Amount
            $today_ecourier_product_value       = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2) ', [date("Y-m-d")] )->sum('total_collect_amount');
            $data['todayProductValue']          = number_format((float) ($today_ecourier_product_value), 2, '.', '');

            // Today collect amount = Today customer collect amount
            $today_ecourier_collect_amount      = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2)', [date("Y-m-d")])->sum('customer_collect_amount');
            $data['todayCollectionAmount']      = number_format((float) ($today_ecourier_collect_amount), 2, '.', '');

            /** 2nd Row */
            // Total Product Value = Total collect amount
            $total_ecourier_product_value       = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) ')->sum('total_collect_amount');
            $data['totalProductValue']          = number_format((float) ($total_ecourier_product_value), 2, '.', '');

            // Total Collect Amount = Total customer collect amount
            $total_ecourier_collect_amount      = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2)')->sum('customer_collect_amount');
            $data['totalCollectionAmount']      = number_format((float) ($total_ecourier_collect_amount), 2, '.', '');


            /** 3rd Row */
            $data['merchant_statement_url'] = route('admin.account.merchantPaymentDeliveryStatement');
            // Today paid amount account to merchant
            $today_collect_amount_paid_merchant     = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2) and payment_type = 5', [date("Y-m-d")])->sum('customer_collect_amount');

//            $today_collect_amount_paid_merchant     = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type = 5')
//                                                            ->whereBetween('parcel_date', ['2021-07-01', '2021-07-01'])
//                                                            ->sum('customer_collect_amount');

//            dd($today_collect_amount_paid_merchant);
//
            $today_charge_amount_paid_merchant      = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2) and payment_type = 5', [date("Y-m-d")])->sum('total_charge');

//            $today_charge_amount_paid_merchant      = (double) Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type = 5')
//                                                                ->whereBetween('parcel_date', ['2021-07-01', '2021-07-01'])
//                                                                ->sum('total_charge');

            $data['todayPaidToMerchant']            = number_format((float) ($today_collect_amount_paid_merchant - $today_charge_amount_paid_merchant), 2, '.', '');

//            dd($today_collect_amount_paid_merchant, $today_charge_amount_paid_merchant, $data['todayPaidToMerchant']);

            // Total Paid amount account to merchant
            $total_collect_amount_paid_merchant     = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type = 5')->sum('customer_collect_amount');
            $total_charge_amount_paid_merchant      = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type = 5')->sum('total_charge');
            $data['totalPaidToMerchant']            = number_format((float) ($total_collect_amount_paid_merchant - $total_charge_amount_paid_merchant), 2, '.', '');

            // Total pending amount in accounts
            $total_collect_amount_pending_merchant      = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type IN (4,6)')->sum('customer_collect_amount');
            $total_charge_amount_pending_merchant       = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) and payment_type IN (4,6)')->sum('total_charge');
            $data['totalPendingAmount']                 = number_format((float) ($total_collect_amount_pending_merchant - $total_charge_amount_pending_merchant), 2, '.', '');;

            // Today Income Amount after paid merchant
            $data['todayTotalIncome']   = number_format((float) ($today_charge_amount_paid_merchant), 2, '.', '');;

            // Total Income after paid merchant
            $data['totalIncome']        = number_format((float) ($total_charge_amount_paid_merchant), 2, '.', '');;

//        }else {
            /** E-courier */

//        $data['todayPickupRequest']     = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (1,4,5,6,7,8,10,9,11)', [date("Y-m-d")])->select('id')->count();

            $data['parcel_url'] = route('admin.parcel.allParcelList');

//            $data['todayPickupRequest'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status NOT IN (2,3,4)', [date("Y-m-d")])->select('id')->count();
            $data['todayPickupRequest'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status IN (1)', [date("Y-m-d")])->select('id')->count();
            $data['todayPickupComplete'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status >= 11', [date("Y-m-d")])->select('id')->count();
            $data['todayPickupPending'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status NOT IN (2,3,4) and status < 11', [date("Y-m-d")])->select('id')->count();

            $data['todayPickupCancel'] = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (9)', [date("Y-m-d")])->select('id')->count();

            $data['etodayDeliveryParcels'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status > 11 and status NOT IN (13, 15)', [date("Y-m-d")])->select('id')->count();
            $data['etodayDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status >= 25 and delivery_type in (1,2)', [date("Y-m-d")])->select('id')->count();
            $data['etodayDeliveryPending'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [date("Y-m-d")])->select('id')->count();
            $data['etodayDeliveryCancel'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status >= 25 and delivery_type = 4', [date("Y-m-d")])->select('id')->count();

            $data['etotalDeliveryParcels'] = Parcel::whereRaw('delivery_branch_id != "" and status > 11 and status NOT IN (13, 15)')->select('id')->count();
            $data['etotalDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)')->select('id')->count();
            $data['etotalDeliveryPending'] = Parcel::whereRaw('delivery_branch_id != "" and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )')->select('id')->count();
            $data['etotalDeliveryCancel'] = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type = 4')->select('id')->count();

            $total_ecourier_collection = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)')->sum('customer_collect_amount');
            $data['ecourierTotalCollectAmount'] = number_format((float)($total_ecourier_collection), 2, '.', '');

            $ecourier_collection_paid_to_account = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2) and payment_type in (2, 4, 5, 6)')->sum('customer_collect_amount');
            $data['ecourierPaidToAccount'] = number_format((float)$ecourier_collection_paid_to_account, 2, '.', '');

            $data['ecourierBalanceCollectAmount'] = number_format((float)($total_ecourier_collection - $ecourier_collection_paid_to_account), 2, '.', '');


            /** Traditional */
            $data['totalDeliveryParcels'] = BookingParcel::whereRaw('status in (8)')->select('id')->count();
            $data['todayDeliveryParcels'] = BookingParcel::whereRaw('DATE(updated_at) = ? and status in (8)', [date("Y-m-d")])->select('id')->count();

            $customer_collection_amount = BookingParcel::whereRaw('status in (8)')->sum('customer_collected_amount');
            $customer_due_amount = BookingParcel::whereRaw('status in (8)')->sum('customer_due_amount');
            $total_delivery_collection = $customer_collection_amount + $customer_due_amount;
            $data['totalDeliveryCollectionAmount'] = number_format((float)$total_delivery_collection, 2, '.', '');

            $data['totalBookingParcels'] = BookingParcel::select('id')->count();
            $data['todayBookingParcels'] = BookingParcel::whereRaw('booking_date = ?', [date("Y-m-d")])->select('id')->count();

            $total_booking_collection = BookingParcel::sum('paid_amount');
            $data['totalBookingParcelsCollectAmount'] = number_format((float)$total_booking_collection, 2, '.', '');
            $data['totalCollectAmount'] = number_format((float)($total_delivery_collection + $total_booking_collection), 2, '.', '');

            $data['totalRejectParcels'] = BookingParcel::whereRaw('status in (0)')->select('id')->count();
            $data['todayRejectParcels'] = BookingParcel::whereRaw('DATE(updated_at) = ? and status in (0)', [date("Y-m-d")])->select('id')->count();

            $collection_accounts_amount = BookingParcelPayment::whereRaw('payment_status = ?', [2])->sum('receive_amount');
            $data['accountsTotalBalance'] = number_format((float)$collection_accounts_amount, 2, '.', '');

//        $data['balanceCollectAmount'] = number_format((float) ($data['totalCollectAmount'] - $collection_paid_to_account), 2, '.', '');
//        }

        return json_encode($data);
    }


    public function adminDashboardCounterEvent()
    {
        $counter_data = $this->returnDashboardCounterForAdmin();

        event(new AdminDashboardRealTimeCounter(json_decode($counter_data)));


        return "admin dashboard counter";

    }


    /** For Dashboard Counter */

    protected function returnDashboardCounterForBranch($branch_id)
    {
        $data = array();

        $from_date  = date("Y-m-d");
        $to_date    = date("Y-m-d");
        $data['token']          = csrf_token();

        $data['riders']                 = Rider::where('branch_id', $branch_id)->select('id')->count();
        $data['riderUrl']               = route('branch.riderListByBranch');

        $data['merchants']              = Merchant::where('branch_id', $branch_id)->select('id')->count();
        $data['active_merchants']       = Merchant::where('branch_id', $branch_id)->where('status', 1)->select('id')->count();
        $data['merchantUrl']            = route('branch.merchantListByBranch');

        $data['brancUsers']             = BranchUser::where('branch_id', $branch_id)->select('id')->count();
        $data['branches']               = Branch::select('id')->count();

        /** E-courier */
        $data['pickupParcels']          = Parcel::whereRaw('pickup_branch_id = ? and parcel_date = ? and status in (1,4,5,6,7,8,9,10)', [$branch_id, date("Y-m-d")])->select('id')->count();
        $data['deliveryParcels']        = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status in (13,16,17,18,19,20,21,22,23,24,25)', [$branch_id, date("Y-m-d")])->select('id')->count();
        $data['rejectParcels']          = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status in (15)', [$branch_id, date("Y-m-d")])->select('id')->count();


        /** 1st Row */
        $data['filterUrl']              = route('branch.parcel.filterList');
        // Today's Pickup Request only merchant request
        $data['todayPickupRequest']     = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status IN (1)', [$branch_id, date("Y-m-d")])
                                                ->whereHas('merchant', function ($q) {
                                                    $q->where('status', 1);
                                                })
                                                ->select('id')->count();

        //Total Pickup Request a to z
        $data['totalPickupRequest']     = Parcel::whereRaw('pickup_branch_id = ? and status NOT IN (2,3,4)', [$branch_id])
                                                //->whereBetween('parcel_date', [$from_date, $to_date])
                                                ->whereHas('merchant', function ($q) {
                                                    $q->where('status', 1);
                                                })
                                                ->select('id')->count();

        //dd($data['totalPickupRequest']);

        // Today's pickup done
        $data['todayPickupComplete']    = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status >= 11', [$branch_id, date("Y-m-d")])
                                                    ->whereHas('merchant', function ($q) {
                                                        $q->where('status', 1);
                                                    })->select('id')->count();
        // Today's pickup pending
        $data['todayPickupPending']     = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status NOT IN (2,3,4) and status < 11', [$branch_id, date("Y-m-d")])
                                                    ->whereHas('merchant', function ($q) {
                                                        $q->where('status', 1);
                                                    })->select('id')->count();

        $data['todayPickupCancel']      = Parcel::whereRaw('pickup_branch_id = ? and parcel_date = ? and status in (9)', [$branch_id, date("Y-m-d")])->select('id')->count();


        /** 2nd Row */
        // Total Pending Parcel
        $data['pendingParcels']         = Parcel::whereRaw('pickup_branch_id = ? and status NOT IN (2,3,4) and status < 11', [$branch_id])->select('id')->count();

        // New Parcel = new entry parcel
        $data['newParcels']             = Parcel::whereRaw('pickup_branch_id = ? and date = ?', [$branch_id, date("Y-m-d")])->select('id')->count();

        // Total Parcel for Delivery = all parcel delivery branch without delivery complete
        $data['totalParcelForDelivery'] = Parcel::whereRaw('delivery_branch_id = ? and status > 11 and status < 25', [$branch_id])->select('id')->count();

        // Today's rider run list parcel pickup, delivery, Return
        $data['todayRiderRunListParcels'] = Parcel::whereRaw('(pickup_branch_id = ? or delivery_branch_id = ? or return_branch_id = ?) and parcel_date = ? and status IN (6,8,9,10,17,19,20,21,22,23,24,31,33,34,35)', [$branch_id, $branch_id, $branch_id, date("Y-m-d")])->select('id')->count();

        // Today's rider run list parcel pickup, delivery, Return
        $data['totalRiderRunListParcels'] = Parcel::whereRaw('(pickup_branch_id = ? or delivery_branch_id = ? or return_branch_id = ?) and status IN (6,8,9,10,17,19,20,21,22,23,24,31,33,34,35)', [$branch_id, $branch_id, $branch_id])->select('id')->count();

        /** 3rd Row */
        // Today's Delivery parcel = Complete Delivery
        $data['etodayDeliveryComplete']     = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status >= 25 and delivery_type in (1,2)', [$branch_id, date("Y-m-d")])->select('id')->count();

        // Today's Delivery Pending = Pending Delivery
        $data['etodayDeliveryPending']      = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [$branch_id, date("Y-m-d")])->select('id')->count();

        // Today's Cancel Parcel = Cancel Delivery
        $data['etodayDeliveryCancel']       = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status IN (25,26,27,29) and delivery_type = 4', [$branch_id, date("Y-m-d")])->select('id')->count();

        /** 4th Row */
        // Total Delivery parcel = Complete Delivery
        $data['etotalDeliveryComplete']     = Parcel::whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id])->select('id')->count();

        // Total Delivery Pending = Pending Delivery
        $data['etotalDeliveryPending']      = Parcel::whereRaw('delivery_branch_id = ? and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [$branch_id])->select('id')->count();

        // Total Cancel Parcel = Cancel Delivery
        $data['etotalDeliveryCancel']       = Parcel::whereRaw('delivery_branch_id = ? and status IN (25,26,27,29) and delivery_type = 4', [$branch_id])->select('id')->count();

//        $data['etotalDeliveryParcels']  = Parcel::whereRaw('delivery_branch_id = ? and status > 11 and status NOT IN (13, 15)', [$branch_id])->select('id')->count();

        /** 5th Row */
        // Today Product Value
        $today_ecourier_product_value           = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and (status > 11 and status < 25) ', [$branch_id, date("Y-m-d")] )->sum('total_collect_amount');
        $data['ecourierTodayProductValue']     = number_format((float) ($today_ecourier_product_value), 2, '.', '');

        // Today Collection Amount
        $today_ecourier_collection              = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status >= 25 and delivery_type in (1,2) ', [$branch_id, date("Y-m-d")] )->sum('customer_collect_amount');
        $data['ecourierTodayCollectAmount']     = number_format((float) ($today_ecourier_collection), 2, '.', '');

        // Total Collection Amount
        $total_ecourier_collection              = Parcel::whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id] )->sum('customer_collect_amount');
        $data['ecourierTotalCollectAmount']     = number_format((float) ($total_ecourier_collection), 2, '.', '');

//        $ecourier_collection_paid_to_account    = Parcel::whereRaw('delivery_branch_id = ? and delivery_type in (1,2) and payment_type in(2, 4, 5, 6)', [$branch_id] )->sum('customer_collect_amount');
//        $data['ecourierPaidToAccount']          = number_format((float) $ecourier_collection_paid_to_account, 2, '.', '');
//
//        $data['ecourierBalanceCollectAmount']   = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_account), 2, '.', '');

        /** 6th Row */
        // Today's Return parcel = Complete Return today
        $data['etodayReturnComplete']     = Parcel::whereRaw('return_branch_id = ? and parcel_date = ? and status >= 36 and delivery_type in (4)', [$branch_id, date("Y-m-d")])->select('id')->count();

        // Total Return parcel = Complete Return all
        $data['etotalReturnComplete']     = Parcel::whereRaw('return_branch_id = ? and status >= 36 and delivery_type in (4)', [$branch_id])->select('id')->count();

        // Total Pending Return Parcels = Pending Return
        $data['etotalPendingReturn']      = Parcel::whereRaw('return_branch_id = ? and status IN (28,30,31,32,33,34,35) and delivery_type IN (4)', [$branch_id])->select('id')->count();


        /** Traditional */
        $data['totalDeliveryParcels']           = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id] )->select('id')->count();
        $data['todayDeliveryParcels']           = BookingParcel::whereRaw('receiver_branch_id = ? and DATE(updated_at) = ? and status in (8)', [$branch_id, date("Y-m-d")] )->select('id')->count();

        $customer_collection_amount             = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id] )->sum('customer_collected_amount');
        $customer_due_amount                    = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id] )->sum('customer_due_amount');
        $total_delivery_collection              = $customer_collection_amount + $customer_due_amount;
        $data['totalDeliveryCollectionAmount']  = number_format( (float) $total_delivery_collection, 2, '.', '');

        $data['totalBookingParcels']            = BookingParcel::whereRaw('sender_branch_id = ?', [$branch_id] )->select('id')->count();
        $data['todayBookingParcels']            = BookingParcel::whereRaw('sender_branch_id = ? and booking_date = ?', [$branch_id, date("Y-m-d")] )->select('id')->count();

        $total_booking_collection                   = BookingParcel::whereRaw('sender_branch_id = ?', [$branch_id] )->sum('paid_amount');
        $data['totalBookingParcelsCollectAmount']   = number_format((float) $total_booking_collection, 2, '.', '');
        $data['totalCollectAmount']                 = number_format((float) ($total_delivery_collection + $total_booking_collection), 2, '.', '');

        $collection_paid_to_account         = BookingParcelPayment::whereRaw('branch_id = ? and payment_status = ?', [$branch_id, 2] )->sum('receive_amount');
        $data['paidToAccount']              = number_format((float) $collection_paid_to_account, 2, '.', '');
        $data['balanceCollectAmount']       = number_format((float) ($data['totalCollectAmount'] - $collection_paid_to_account), 2, '.', '');


        return json_encode($data);
    }


    public function branchDashboardCounterEvent($branch_id)
    {
        $counter_data = $this->returnDashboardCounterForBranch($branch_id);

        event(new BranchDashboardRealTimeCounter(json_decode($counter_data), $branch_id));


        return "Branch dashboard counter";

    }


    /** For Merchant Dashboard Counter */

    protected function returnDashboardCounterForMerchant($merchant_id)
    {
        $data               = [];

        $data['total_parcel']             = Parcel::where('merchant_id', $merchant_id)
            ->count();

        $data['total_cancel_parcel']    = Parcel::where('merchant_id', $merchant_id)
            ->where('status', 3)
            ->count();

        $data['total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [3,11])
            ->count();

        $data['total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "")', [3,11,24])
            ->count();

        $data['total_delivery_parcel']  = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3,1,2,3,4])
            ->count();

        $data['total_delivery_complete_parcel']  = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
            ->count();

        $data['total_partial_delivery_complete']  = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?) and payment_type = ?', [25,2,5])
            ->count();

        $data['total_pending_delivery']  = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status > 11 and delivery_type in (?)', [3])
            ->count();

        $data['total_return_parcel']    = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?)', [25,2,4])
            ->count();

        $data['total_return_complete_parcel']    = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status = ? and delivery_type in (?,?)', [36,2,4])
            ->count();

        $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
            ->sum('merchant_paid_amount');

//        $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
//                                                    ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
//                                                    ->toSql();
//
//        dd($merchant_id, $data['total_pending_collect_amount'] );



        $data['total_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
            ->sum('merchant_paid_amount');

        return json_encode($data);
    }


    public function merchantDashboardCounterEvent($merchant_id)
    {
        $counter_data = $this->returnDashboardCounterForMerchant($merchant_id);

        event(new MerchantDashboardRealTimeCounter(json_decode($counter_data), $merchant_id));


        return "Merchant dashboard counter";

    }


}

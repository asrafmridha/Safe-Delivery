<?php

namespace App\Http\Controllers;

use App\Events\AdminDashboardRealTimeCounter;
use App\Events\BranchDashboardRealTimeCounter;
use App\Events\MerchantDashboardRealTimeCounter;
use App\Jobs\SendMessage;
use App\Models\BookingParcelPayment;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\DeliveryBranchTransfer;
use App\Models\Merchant;
use App\Models\ParcelLog;
use App\Models\PathaoOrder;

use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Parcel;
use App\Models\BookingParcel;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\ParcelPaymentRequest;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ReturnBranchTransfer;
use App\Models\WeightPackage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use App\Models\Expense;
use App\Models\ParcelPickupRequest;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function setMessage($message, $type)
    {
        session()->flash('message', $message);
        session()->flash('type', $type);
    }

    function sweetAlertMessage($type, $message, $title = '')
    {
        session()->flash('alert-title', $title);
        session()->flash('alert-message', $message);
        session()->flash('alert-type', $type);
    }


    public function send_sms($phone, $message)
    {
        $details['phone'] = $phone;
        $details['message'] = $message;
        
       return send_bl_sms($phone, $message);
        
      //  For sms stop comment  dispatch
        // dispatch(new SendMessage($details));
        // return 1;


        // https://portal.metrotel.com.bd/smsapi?api_key=C200110161545f4b0a3961.52619769&type=text&contacts=8801757769498&msg=Test Message&senderid=8809612116655

        /*$api_key = urlencode("C200110161545f4b0a3961.52619769");
        $senderid = urlencode("8809612116655");
        $phone = urlencode('880' . substr(preg_replace('/\D/', '', $phone), -10));
        $message = urlencode($message);

        $url = "https://portal.metrotel.com.bd/smsapi";
        $url .= "?api_key=" . $api_key;
        $url .= "&type=text";
        $url .= "&senderid=" . $senderid;
        $url .= "&contacts=" . $phone;
        $url .= "&msg=" . $message;*/

        // return $url;

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // $response = curl_exec($ch);
        // curl_close($ch);

        // return $response;
    }



    public function send_reg_sms($phone, $message)
    {
        $details['phone'] = $phone;
        $details['message'] = $message;
        return send_signup_sms($phone, $message);
        
    }







    /*
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
     */





 // =============== new codes For Parcel Request starts ==================
    function returnUniquePickupRequestInvoice(){
        $lastPickupRequest = ParcelPickupRequest::orderBy('id', 'desc')->first();

        if(!empty($lastPickupRequest)){
            $get_serial = explode("-", $lastPickupRequest->pickup_request_invoice);
            $current_serials = $get_serial[1] +1;
            $pickup_request_invoice = 'Pickup-'.str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        }
        else{
            $pickup_request_invoice = 'Pickup-00001';
        }
        return $pickup_request_invoice;
    }





    function returnUniqueParcelInvoice()
    {
        $lastParcel = Parcel::orderBy('id', 'desc')->first();
        $currentDate = date("ymd");
        if (!empty($lastParcel)) {
            $get_serial = substr($lastParcel->parcel_invoice, 9, 30);
            $random_string = strtoupper($this->generateRandomString(3));
            $get_serial = strtoupper(base_convert(base_convert($get_serial, 36, 10) + 1, 10, 36));
            $parcel_invoice = $currentDate . $random_string . str_pad($get_serial, 4, '0', STR_PAD_LEFT);
        } else {
            $parcel_invoice = $currentDate . 'ANZ0001';
        }
        return $parcel_invoice;
    }

    function returnUniquePaymentRequestInvoice()
    {
        $lastPaymentRequest = ParcelPaymentRequest::orderBy('id', 'desc')->first();

        if (!empty($lastPaymentRequest)) {
            $get_serial = explode("-", $lastPaymentRequest->payment_request_invoice);
            $current_serials = $get_serial[1] + 1;
            $payment_request_invoice = 'Payment-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $payment_request_invoice = 'Payment-00001';
        }
        return $payment_request_invoice;
    }

    function returnUniqueBookingParcelInvoice($sdname, $rdname)
    {
        $lastParcel = BookingParcel::orderBy('id', 'desc')->first();

        $sdisname = strtoupper(substr($sdname, 0, 2));
        $rdisname = strtoupper(substr($rdname, 0, 2));
        $currentDate = date("Ymd");

        $strcode = 'METTRO' . $sdisname . $rdisname . $currentDate;
        if (!empty($lastParcel)) {
            $get_serial = explode("-", $lastParcel->parcel_code);
            $current_serials = $get_serial[1] + 1;
            $parcel_invoice = $strcode . '-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $parcel_invoice = $strcode . '-00001';
        }
        return $parcel_invoice;
    }

    function returnUniqueRiderRunInvoice()
    {
        $lastRiderRun = RiderRun::orderBy('id', 'desc')->first();

        if (!empty($lastRiderRun)) {
            $get_serial = explode("-", $lastRiderRun->run_invoice);
            $current_serials = $get_serial[1] + 1;
            $run_invoice = 'RUN-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $run_invoice = 'RUN-00001';
        }
        return $run_invoice;
    }
    
       function returnUniquePathaoOrderNo()
    {
        $lastPathaoOrder = PathaoOrder::orderBy('id', 'desc')->first();

        if (!empty($lastPathaoOrder)) {
            $get_serial = explode("-", $lastPathaoOrder->order_no);
            $current_serials = $get_serial[1] + 1;
            $run_invoice = 'Order-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $run_invoice = 'Order-00001';
        }
        return $run_invoice;
    }
    
    

    function returnUniqueDeliveryPaymentInvoice()
    {
        $lastDeliveryPayment = ParcelDeliveryPayment::orderBy('id', 'desc')->first();

        if (!empty($lastDeliveryPayment)) {
            $get_serial = explode("-", $lastDeliveryPayment->payment_invoice);
            $current_serials = $get_serial[1] + 1;
            $payment_invoice = 'PAY-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $payment_invoice = 'PAY-00001';
        }
        return $payment_invoice;
    }

    function returnUniqueParcelPaymentBillNo()
    {
        $lastParcelPayment = BookingParcelPayment::orderBy('id', 'desc')->first();

        if (!empty($lastParcelPayment)) {
            $get_serial = explode("-", $lastParcelPayment->bill_no);
            $current_serials = $get_serial[1] + 1;
            $payment_invoice = 'PAY-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $payment_invoice = 'PAY-00001';
        }
        return $payment_invoice;
    }

    function returnUniqueMerchantDeliveryPaymentInvoice()
    {
        $lastDeliveryPayment = ParcelMerchantDeliveryPayment::orderBy('id', 'desc')->first();

        if (!empty($lastDeliveryPayment)) {
            $get_serial = explode("-", $lastDeliveryPayment->merchant_payment_invoice);
            $current_serials = $get_serial[1] + 1;
            $merchant_payment_invoice = 'MPAY-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $merchant_payment_invoice = 'MPAY-00001';
        }
        return $merchant_payment_invoice;
    }

    function returnUniqueMerchantId()
    {
        $merchant = Merchant::orderBy('id', 'desc')->first();

        if (!empty($merchant)) {
            $get_serial = explode("-", $merchant->m_id);
            $current_serials = $get_serial[1] + 1;
            $m_id = 'M-' . str_pad($current_serials, 4, '0', STR_PAD_LEFT);
        } else {
            $m_id = 'M-0001';
        }
        return $m_id;
    }

    function returnUniqueRiderId()
    {
        $rider = Rider::orderBy('id', 'desc')->first();
        if (!empty($rider)) {
            $get_serial = explode("-", $rider->r_id);
            $current_serials = $get_serial[1] + 1;
            $r_id = 'R-' . str_pad($current_serials, 4, '0', STR_PAD_LEFT);
        } else {
            $r_id = 'R-0001';
        }
        return $r_id;
    }

    function returnUniqueBranchTransferInvoice()
    {
        $lastDeliveryBranchTransfer = DeliveryBranchTransfer::orderBy('id', 'desc')->first();

        if (!empty($lastDeliveryBranchTransfer)) {
            $get_serial = explode("-", $lastDeliveryBranchTransfer->delivery_transfer_invoice);
            $current_serials = $get_serial[1] + 1;
            $invoice = 'Transfer-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $invoice = 'Transfer-00001';
        }
        return $invoice;
    }

    function returnUniqueReturnTransferInvoice()
    {
        $lastDeliveryBranchTransfer = ReturnBranchTransfer::orderBy('id', 'desc')->first();

        if (!empty($lastDeliveryBranchTransfer)) {
            $get_serial = explode("-", $lastDeliveryBranchTransfer->return_transfer_invoice);
            $current_serials = $get_serial[1] + 1;
            $invoice = 'RTransfer-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $invoice = 'RTransfer-00001';
        }
        return $invoice;
    }

    function returnUniqueWeightPackageID()
    {
        $weightPackage = WeightPackage::orderBy('id', 'desc')->first();
        if (!empty($weightPackage)) {
            $get_serial = explode("-", $weightPackage->wp_id);
            $current_serials = $get_serial[1] + 1;
            $wp_id = 'WP-' . str_pad($current_serials, 2, '0', STR_PAD_LEFT);
        } else {
            $wp_id = 'WP-01';
        }
        return $wp_id;
    }


    public static function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function uploadFile($image, $upload_path)
    {
        $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
        $image->move('public/uploads/' . $upload_path, $image_name);
//        $image_path = 'public/uploads/' . $upload_path . '/' . $image_name;
//        Image::make($image)->save($image_path);
        return $image_name;
    }


    function removeFile($file, $upload_path)
    {
        if (!empty($file)) {
            $old_file_path = str_replace('\\', '/', public_path()) . '/uploads/' . $upload_path . '/' . $file;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
    }


    /** For Dashboard Counter */

    protected function returnDashboardCounterForAdmin()
    {
        $data = array();
        $data['token'] = csrf_token();


        $data['branches'] = Branch::select('id')->count();
        $data['branch_url'] = route('admin.branch.index');
        $data['riders'] = Rider::select('id')->count();
        $data['rider_url'] = route('admin.rider.index');
        $data['merchants'] = Merchant::select('id')->count();
        $data['active_merchants'] = Merchant::where('status', 1)->select('id')->count();
        $data['merchant_url'] = route('admin.merchant.index');
        $data['warehouses'] = Warehouse::select('id')->count();
        $data['warehouse_url'] = route('admin.warehouse.index');


//        $admin_user = auth()->guard('admin')->user();
//        $admin_type = $admin_user->type;
//        if($admin_type == 3) {
        /** 1st Row */
        // Today Product Value = Today Total collect Amount
        $today_ecourier_product_value = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2) ', [date("Y-m-d")])->sum('total_collect_amount');
        $data['todayProductValue'] = number_format((float)($today_ecourier_product_value), 2, '.', '');

        // Today collect amount = Today customer collect amount
        $today_ecourier_collect_amount = Parcel::whereRaw('parcel_date = ? and status >= 25 and delivery_type IN (1,2)', [date("Y-m-d")])->sum('customer_collect_amount');
        $data['todayCollectionAmount'] = number_format((float)($today_ecourier_collect_amount), 2, '.', '');

        /** 2nd Row */
        // Total Product Value = Total collect amount
        $total_ecourier_product_value = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2) ')->sum('total_collect_amount');
        $data['totalProductValue'] = number_format((float)($total_ecourier_product_value), 2, '.', '');

        // Total Collect Amount = Total customer collect amount
        $total_ecourier_collect_amount = Parcel::whereRaw('status >= 25 and delivery_type IN (1,2)')->sum('customer_collect_amount');
        $data['totalCollectionAmount'] = number_format((float)($total_ecourier_collect_amount), 2, '.', '');
        // Total Expense
        $total_expense = Expense::sum('amount');
        $data['total_expense'] = number_format((float)($total_expense), 2, '.','');

        // Total Expense current month
        $total_expense_month =  Expense::select('amount')->whereMonth('date', Carbon::now()->month)->get()->sum('amount');
        $data['total_expense_month'] = number_format((float)($total_expense_month), 2, '.','');

        // Total Expense current date
        $total_expense_date = Expense::select('amount')->whereDate('date', Carbon::now())->sum('amount');
        $data['total_expense_date'] = number_format((float)($total_expense_date), 2, '.','');

        /** 3rd Row */
        $data['merchant_statement_url'] = route('admin.account.merchantPaymentDeliveryStatement');

        // Today paid amount account to merchant
        $today_merchant_paid_details = ParcelMerchantDeliveryPaymentDetail::select(DB::raw('SUM(parcel_merchant_delivery_payment_details.paid_amount) As paid_amount, SUM(parcels.total_charge) As total_charge'))
            ->leftJoin('parcels', 'parcels.id', '=', 'parcel_id')
            ->whereDate('parcel_merchant_delivery_payment_details.date_time', date("Y-m-d"))
            ->where('parcel_merchant_delivery_payment_details.status', 2)
            ->first();

        $today_collect_amount_paid_merchant = $today_merchant_paid_details->paid_amount;
        $today_charge_amount_paid_merchant = $today_merchant_paid_details->total_charge;
        $data['todayPaidToMerchant'] = number_format((float)($today_collect_amount_paid_merchant), 2, '.', '');
//            $data['todayPaidToMerchant']            = number_format((float) ($today_collect_amount_paid_merchant - $today_charge_amount_paid_merchant), 2, '.', '');


        // Total Paid amount account to merchant
        $total_merchant_paid_details = ParcelMerchantDeliveryPaymentDetail::select(DB::raw('SUM(parcel_merchant_delivery_payment_details.paid_amount) As paid_amount, SUM(parcels.total_charge) As total_charge'))
            ->leftJoin('parcels', 'parcels.id', '=', 'parcel_id')
            ->where('parcel_merchant_delivery_payment_details.status', 2)
            ->first();
        $total_collect_amount_paid_merchant = $total_merchant_paid_details->paid_amount;
        $total_charge_amount_paid_merchant = $total_merchant_paid_details->total_charge;
        $data['totalPaidToMerchant'] = number_format((float)($total_collect_amount_paid_merchant), 2, '.', '');
//            $data['totalPaidToMerchant']            = number_format((float) ($total_collect_amount_paid_merchant - $total_charge_amount_paid_merchant), 2, '.', '');

        // Total pending amount in accounts
        $total_merchant_paid_pending_details = ParcelMerchantDeliveryPaymentDetail::select(DB::raw('SUM(parcel_merchant_delivery_payment_details.paid_amount) As paid_amount, SUM(parcels.total_charge) As total_charge'))
            ->leftJoin('parcels', 'parcels.id', '=', 'parcel_id')
            ->where('parcel_merchant_delivery_payment_details.status', 1)
            ->first();

        $total_collect_amount_pending_merchant = $total_merchant_paid_pending_details->paid_amount;
        $total_charge_amount_pending_merchant = $total_merchant_paid_pending_details->total_charge;
        $data['totalPendingAmount'] = number_format((float)($total_collect_amount_pending_merchant), 2, '.', '');;
//            $data['totalPendingAmount']                 = number_format((float) ($total_collect_amount_pending_merchant - $total_charge_amount_pending_merchant), 2, '.', '');;

        // Today Income Amount after paid merchant
        $data['todayTotalIncome'] = number_format((float)($today_charge_amount_paid_merchant), 2, '.', '');;

        // Total Income after paid merchant
        $data['totalIncome'] = number_format((float)($total_charge_amount_paid_merchant), 2, '.', '');;


        // Total Paid amount account to merchant
        /*        $this_month_merchant_paid_details = ParcelMerchantDeliveryPaymentDetail::select(DB::raw('SUM(parcel_merchant_delivery_payment_details.paid_amount) As paid_amount, SUM(parcels.total_charge) As total_charge'))
                    ->leftJoin('parcels', 'parcels.id', '=', 'parcel_id')
                    ->where('parcel_merchant_delivery_payment_details.status', 2)
                    ->whereMonth('parcel_merchant_delivery_payment_details.date_time', date("Y-m-d"))
                    ->first();*/
        $this_month_merchant_paid_details = ParcelMerchantDeliveryPaymentDetail::select(DB::raw('SUM(parcel_merchant_delivery_payment_details.paid_amount) As paid_amount, SUM(parcels.total_charge) As total_charge'))
            ->leftJoin('parcels', 'parcels.id', '=', 'parcel_id')
            ->whereDate('parcel_merchant_delivery_payment_details.date_time',">=", date("Y-m")."-1")
            ->where('parcel_merchant_delivery_payment_details.status', 2)
            ->first();
        $this_month_charge_amount_paid_merchant = $this_month_merchant_paid_details->total_charge;
        $data['thisMonthIncome'] = number_format((float)($this_month_charge_amount_paid_merchant), 2, '.', '');;


//        }else {


        /** E-courier */
        /** 1st Row */
        $data['filterUrl'] = route('admin.parcel.filterList');
        // total Pickup Request only merchant request
        $data['totalPickupRequest'] = Parcel::whereRaw('pickup_branch_id != "" and merchant_id != "" and status IN (1)')
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })
            ->select('id')->count();
        // Today's Pickup Request only merchant request
        $data['todayPickupRequest'] = Parcel::whereRaw('pickup_branch_id != "" and  date = ? and status IN (1)', [date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })
            ->select('id')->count();

        //Total Pickup Request a to z
//        $data['totalPickupRequest']     = Parcel::whereRaw('pickup_branch_id != "" and status NOT IN (2,3,4)')
//            //->whereBetween('parcel_date', [$from_date, $to_date])
//            ->whereHas('merchant', function ($q) {
//                $q->where('status', 1);
//            })
//            ->select('id')->count();

        //dd($data['totalPickupRequest']);

        // Today's pickup done
        $data['todayPickupComplete'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status in (11,13,15)', [date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();

        // Total pickup done
        $data['totalPickupComplete'] = Parcel::whereRaw('pickup_branch_id != "" and status >= 11')
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();
        // Today's pickup pending
        $data['todayPickupPending'] = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status NOT IN (2,3,4) and status < 11', [date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();

        $data['todayPickupCancel'] = Parcel::whereRaw('pickup_branch_id != "" and parcel_date = ? and status in (9)', [date("Y-m-d")])->select('id')->count();


        /** 2nd Row */
        // Today New Parcel = Previous day pickup done parcel
//        $data['todayNewParcels']             = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status = 11', [date("Y-m-d", strtotime("-1 days"))])->select('id')->count();
        $data['todayNewParcels'] = ParcelLog::whereRaw('pickup_branch_id != "" and date = ? and status = 11', [date("Y-m-d", strtotime("-1 days"))])->select('id')->count();

        // Previous Pending Parcel
//        $data['previousPendingParcels']         = Parcel::whereRaw('pickup_branch_id != "" and date = ? and status > 11 and status < 25', [date("Y-m-d", strtotime("-1 days"))])->select('id')->count();
//        $data['previousPendingParcels']         = Parcel::whereRaw('pickup_branch_id != ""  and status > 11 and status < 25')->select('id')->count();
        $data['previousPendingParcels'] = Parcel::whereRaw('delivery_branch_id != "" and date < ? and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)', [date("Y-m-d", strtotime("-1 days"))])->select('id')->count();

        // Today Parcel for delivery
//        $data['todayParcelForDelivery'] = $data['todayNewParcels'];
        $data['todayParcelForDelivery'] = Parcel::whereRaw('delivery_branch_id != "" and date = ? and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)', [date("Y-m-d")])->select('id')->count();

        // Total Parcel for Delivery = all parcel delivery branch without delivery complete
        $data['totalParcelForDelivery'] = Parcel::whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)')->select('id')->count();

        /** 3rd Row */
        // Today's Delivery parcel = Complete Delivery
        $data['etodayDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id != "" and delivery_date = ? and status >= 25 and delivery_type in (1,2)', [date("Y-m-d")])->select('id')->count();

        // Today's Delivery Pending = Pending Delivery
        $data['etodayDeliveryPending'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [date("Y-m-d")])->select('id')->count();

        // Today's Cancel Parcel = Cancel Delivery
        $data['etodayDeliveryCancel'] = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status IN (25,26,27,29) and delivery_type = 4', [date("Y-m-d")])->select('id')->count();

        /** 4th Row */
        // Total Delivery parcel = Complete Delivery
        $data['etotalDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)')->select('id')->count();
        // Total Cancel Parcel = Cancel Delivery
        $data['etotalDeliveryCancel'] = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type = 4')->select('id')->count();

        // Total Return Parcel = Return Complete
        $data['etotalReturnParcel'] = Parcel::whereRaw('delivery_branch_id != "" and status = 36 and delivery_type = 4')->select('id')->count();

        // Total Pending Return Parcel
        $data['etotalPendingReturnParcels'] = $data['etotalDeliveryCancel'] - $data['etotalReturnParcel'];

        /** 5th Row */
        // Today Product Value
        $today_ecourier_product_value = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and (status > 11 and status < 25) ', [date("Y-m-d")])->sum('total_collect_amount');
        $data['ecourierTodayProductValue'] = number_format((float)($today_ecourier_product_value), 2, '.', '');

        // Today Collection Amount
        $today_ecourier_collection = Parcel::whereRaw('delivery_branch_id != "" and parcel_date = ? and status >= 25 and delivery_type in (1,2) ', [date("Y-m-d")])->sum('customer_collect_amount');
        $data['ecourierTodayCollectAmount'] = number_format((float)($today_ecourier_collection), 2, '.', '');

        // Total Collection Amount
        $total_ecourier_collection = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)')->sum('customer_collect_amount');
        $data['ecourierTotalCollectAmount'] = number_format((float)($total_ecourier_collection), 2, '.', '');

//        $ecourier_collection_paid_to_account    = Parcel::whereRaw('delivery_branch_id = ? and delivery_type in (1,2) and payment_type in(2, 4, 5, 6)', [$branch_id] )->sum('customer_collect_amount');
//        $data['ecourierPaidToAccount']          = number_format((float) $ecourier_collection_paid_to_account, 2, '.', '');
//
//        $data['ecourierBalanceCollectAmount']   = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_account), 2, '.', '');


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
        // $counter_data = $this->returnDashboardCounterForAdmin();
        // event(new AdminDashboardRealTimeCounter(json_decode($counter_data)));
        // return "admin dashboard counter";

    }


    /** For Dashboard Counter */

    protected function returnDashboardCounterForBranch($branch_id)
    {
        $data = array();

        $from_date = date("Y-m-d");
        $to_date = date("Y-m-d");
        $data['token'] = csrf_token();

        $data['riders'] = Rider::where('branch_id', $branch_id)->select('id')->count();
        $data['riderUrl'] = route('branch.riderListByBranch');

        $data['merchants'] = Merchant::where('branch_id', $branch_id)->select('id')->count();
        $data['active_merchants'] = Merchant::where('branch_id', $branch_id)->where('status', 1)->select('id')->count();
        $data['merchantUrl'] = route('branch.merchantListByBranch');

        $data['brancUsers'] = BranchUser::where('branch_id', $branch_id)->select('id')->count();
        $data['branches'] = Branch::select('id')->count();

        /** E-courier */
        /** 1st Row */
        $data['filterUrl'] = route('branch.parcel.filterList');
        // Today's Pickup Request only merchant request
        $data['todayPickupRequest'] = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status IN (1)', [$branch_id, date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })
            ->select('id')->count();

        //Total Pickup Request a to z
        $data['totalPickupRequest'] = Parcel::whereRaw('pickup_branch_id = ? and status NOT IN (2,3,4)', [$branch_id])
            //->whereBetween('parcel_date', [$from_date, $to_date])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })
            ->select('id')->count();

        //dd($data['totalPickupRequest']);

        // Today's pickup done
        $data['todayPickupComplete'] = Parcel::whereRaw('pickup_branch_id = ? and pickup_branch_date = ? and status in (11,13,15)', [$branch_id, date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();

        // Total pickup done
        $data['totalPickupComplete'] = Parcel::whereRaw('pickup_branch_id = ? and status >= 11', [$branch_id])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();
        // Today's pickup pending
        $data['todayPickupPending'] = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status NOT IN (2,3,4) and status < 11', [$branch_id, date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();
        // total Branch Transfer Complete
        $data['totalBranchTransferComplete'] = Parcel::whereRaw('pickup_branch_id = ? and status IN (14)', [$branch_id])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();

        $data['todayPickupCancel'] = Parcel::whereRaw('pickup_branch_id = ? and parcel_date = ? and status in (9)', [$branch_id, date("Y-m-d")])->select('id')->count();


        /** 2nd Row */
        // Today New Parcel = Previous day pickup done parcel
        $data['todayNewParcels'] = Parcel::whereRaw('pickup_branch_id = ? and date = ?', [$branch_id, date("Y-m-d")])->select('id')->count();
//         $data['todayNewParcels']             = Parcel::whereRaw('pickup_branch_id = ? and date = ?', [$branch_id, date("Y-m-d", strtotime("-1 days"))])->select('id')->count();
//        $data['todayNewParcels'] = ParcelLog::whereRaw('pickup_branch_id = ? and date = ? and status = 11', [$branch_id, date("Y-m-d", strtotime("-1 days"))])->select('id')->count();
//        $data['todayNewParcels'] = ParcelLog::whereRaw('pickup_branch_id = ? and date = ? and status = 11', [$branch_id, date("Y-m-d", strtotime("-1 days"))])->select('id')->count();

        // Previous Pending Parcel
        // $data['previousPendingParcels']         = Parcel::whereRaw('pickup_branch_id = ? and date = ? and status > 11 and status < 25', [$branch_id, date("Y-m-d", strtotime("-1 days"))])->select('id')->count();
        $data['previousPendingParcels'] = Parcel::whereRaw('delivery_branch_id = ?  and pickup_branch_date < ? and ((status > 11 and status <= 25 and delivery_type IS NULL) OR (status in (23,25) and delivery_type = 3))', [$branch_id,date("Y-m-d")])->select('id')->count();

        // Today Parcel for delivery
//        $data['todayParcelForDelivery'] = $data['todayNewParcels'] + $data['previousPendingParcels'];
        $data['todayParcelForDelivery'] =  Parcel::whereRaw('delivery_branch_id = ?  and pickup_branch_date = ? and (( status > 11 and status <= 25 and delivery_type IS NULL) OR (status in (23,25) and delivery_type = 3))', [$branch_id,date("Y-m-d")])->select('id')->count();

        // Total Parcel for Delivery = all parcel delivery branch without delivery complete
        $data['totalParcelForDelivery'] = Parcel::whereRaw('delivery_branch_id = ? and ((status > 11 and status <= 25 and delivery_type IS NULL) OR (status in (23,25) and delivery_type = 3))', [$branch_id])->select('id')->count();

        /** 3rd Row */
        // Today's Delivery parcel = Complete Delivery
        $data['etodayDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id = ? and delivery_date = ? and status >= 25 and delivery_type in (1,2)', [$branch_id, date("Y-m-d")])->select('id')->count();

        // Today's Delivery Pending = Pending Delivery
//        $data['etodayDeliveryPending'] = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status > 11 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [$branch_id, date("Y-m-d")])->select('id')->count();
        $data['etodayDeliveryPending'] = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status >= 16 and (status <= 24 OR (status = 25 and delivery_type = 3) )', [$branch_id, date("Y-m-d")])->select('id')->count();
//        dd($data['etodayDeliveryPending']);
        $data['etoday_delivery_pending_url'] = route('branch.parcel.deliveryRiderRunList');

        // Today's Cancel Parcel = Cancel Delivery
        $data['etodayDeliveryCancel'] = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status IN (25,26,27,29) and delivery_type = 4', [$branch_id, date("Y-m-d")])->select('id')->count();
       /* $data['todayBranchTransfer'] = Parcel::whereRaw('pickup_branch_id = ? and parcel_date = ? and status IN (12)', [$branch_id, date("Y-m-d")])
            ->whereHas('merchant', function ($q) {
                $q->where('status', 1);
            })->select('id')->count();*/

//        $data['todayBranchTransfer'] = DeliveryBranchTransfer::whereRaw('from_branch_id = ? and created_at = ? and status IN (3)', [$branch_id, date("Y-m-d")])->select('total_transfer_parcel')->sum('total_transfer_parcel');
//        $data['todayBranchTransfer'] = DeliveryBranchTransfer::whereRaw('from_branch_id = ? and created_at = ?', [$branch_id, date("Y-m-d")])->select('total_transfer_parcel')->sum('total_transfer_parcel');
        $data['todayBranchTransfer'] = DeliveryBranchTransfer::where('from_branch_id', $branch_id)
            ->whereDate('received_date_time', date("Y-m-d"))
            ->select('total_transfer_parcel')->sum('total_transfer_parcel');
//        dd($data['todayBranchTransfer']);

        /** 4th Row */
        // Total Delivery parcel = Complete Delivery
        $data['etotalDeliveryComplete'] = Parcel::whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id])->select('id')->count();
        $data['total_delivery_complete_url'] = route('branch.parcel.completeDeliveryParcelList');

        // Total Cancel Parcel = Cancel Delivery
        // $data['etotalDeliveryCancel']       = Parcel::whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type = 4', [$branch_id])->select('id')->count();
        $data['etotalDeliveryCancel'] = Parcel::whereRaw('return_branch_id = ? and status >= 25 and delivery_type = 4', [$branch_id])->select('id')->count();
        $data['total_delivery_cancel_url'] = route('branch.parcel.returnParcelList');

        // Total Return Parcel = Return Complete
        // $data['etotalReturnParcel']         = Parcel::whereRaw('delivery_branch_id = ? and status = 36 and delivery_type = 4', [$branch_id])->select('id')->count();
        $data['etotalReturnParcel'] = Parcel::whereRaw('return_branch_id = ? and status = 36 and delivery_type = 4', [$branch_id])->select('id')->count();
        $data['etotal_return_url'] = route('branch.parcel.completeReturnParcelList');

        // Total Pending Return Parcel
        $data['etotalPendingReturnParcels'] = $data['etotalDeliveryCancel'] - $data['etotalReturnParcel'];

        /** 5th Row */
        // Today Product Value
        $today_ecourier_product_value = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and (status > 11 and status < 25) ', [$branch_id, date("Y-m-d")])->sum('total_collect_amount');
        $data['ecourierTodayProductValue'] = number_format((float)($today_ecourier_product_value), 2, '.', '');

        // Today Collection Amount
        $today_ecourier_collection = Parcel::whereRaw('delivery_branch_id = ? and parcel_date = ? and status >= 25 and delivery_type in (1,2) ', [$branch_id, date("Y-m-d")])->sum('customer_collect_amount');
        $data['ecourierTodayCollectAmount'] = number_format((float)($today_ecourier_collection), 2, '.', '');

        // Total Collection Amount
        $total_ecourier_collection = Parcel::whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id])->sum('customer_collect_amount');
        $data['ecourierTotalCollectAmount'] = number_format((float)($total_ecourier_collection), 2, '.', '');
        $data['ecourier_total_collect_amount_url'] = route('branch.parcel.deliveryPaymentList');

        // $ecourier_collection_paid_to_account    = Parcel::whereRaw('delivery_branch_id = ? and delivery_type in (1,2) and payment_type in(2, 4, 5, 6)', [$branch_id] )->sum('customer_collect_amount');
        // $data['ecourierPaidToAccount']          = number_format((float) $ecourier_collection_paid_to_account, 2, '.', '');

        // $data['ecourierBalanceCollectAmount']   = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_account), 2, '.', '');


        /** Traditional */
        $data['totalDeliveryParcels'] = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id])->select('id')->count();
        $data['todayDeliveryParcels'] = BookingParcel::whereRaw('receiver_branch_id = ? and DATE(updated_at) = ? and status in (8)', [$branch_id, date("Y-m-d")])->select('id')->count();

        $customer_collection_amount = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id])->sum('customer_collected_amount');
        $customer_due_amount = BookingParcel::whereRaw('receiver_branch_id = ? and status in (8)', [$branch_id])->sum('customer_due_amount');
        $total_delivery_collection = $customer_collection_amount + $customer_due_amount;
        $data['totalDeliveryCollectionAmount'] = number_format((float)$total_delivery_collection, 2, '.', '');

        $data['totalBookingParcels'] = BookingParcel::whereRaw('sender_branch_id = ?', [$branch_id])->select('id')->count();
        $data['todayBookingParcels'] = BookingParcel::whereRaw('sender_branch_id = ? and booking_date = ?', [$branch_id, date("Y-m-d")])->select('id')->count();

        $total_booking_collection = BookingParcel::whereRaw('sender_branch_id = ?', [$branch_id])->sum('paid_amount');
        $data['totalBookingParcelsCollectAmount'] = number_format((float)$total_booking_collection, 2, '.', '');
        $data['totalCollectAmount'] = number_format((float)($total_delivery_collection + $total_booking_collection), 2, '.', '');

        $collection_paid_to_account = BookingParcelPayment::whereRaw('branch_id = ? and payment_status = ?', [$branch_id, 2])->sum('receive_amount');
        $data['paidToAccount'] = number_format((float)$collection_paid_to_account, 2, '.', '');
        $data['balanceCollectAmount'] = number_format((float)($data['totalCollectAmount'] - $collection_paid_to_account), 2, '.', '');


        return json_encode($data);
    }


    public function branchDashboardCounterEvent($branch_id)
    {
        // $counter_data = $this->returnDashboardCounterForBranch($branch_id);
        // event(new BranchDashboardRealTimeCounter(json_decode($counter_data), $branch_id));
        // return "Branch dashboard counter";
    }


    /** For Merchant Dashboard Counter */
    protected function returnDashboardCounterForMerchant($merchant_id)
    {
        $data = [];

        $data['today_total_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();
        $data['total_parcel'] = Parcel::where('merchant_id', $merchant_id)->count();
//            ->where('status', '!=', 3)->count();

        $data['total_cancel_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->where('status', 3)
//            ->whereRaw('status >= 25 and delivery_type = 4')
            ->count();
        $data['today_total_cancel_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->where('status', 3)
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();

        $data['today_total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [3, 11])
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();
        $data['total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [3, 11])
            ->count();

        $data['total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            // ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "")', [3,11,24])
            ->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))')
            ->count();
        $data['today_total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            // ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "")', [3,11,24])
            ->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))')
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();

        $data['total_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3, 1, 2, 3, 4])
            ->count();
        $data['today_total_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3, 1, 2, 3, 4])
            ->whereRaw('delivery_date = ? ', [date("Y-m-d")])
            ->count();

        // $data['today_total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
        //     // ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
        //     ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
        //     ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
        //     ->count();
            
            
        $data['today_total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
    ->where(function ($query) {
        $query->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
              ->orWhereIn('status', [21, 22]);
    })
    // ->whereRaw('parcel_date = ?', [date("Y-m-d")])
    ->where(function($query) {
    $query->whereDate('delivery_branch_date', date('Y-m-d'))
          ->orWhereDate('delivery_rider_date', date('Y-m-d'));
})

    ->count();
    
        // $data['total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
        //     // ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
        //     ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
        //     ->count();
        
        $data['total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
    ->where(function ($query) {
        $query->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
              // ->whereRaw('payment_type = ?', [5]) // চাইলে আলাদা করে এটাও অ্যাড করতে পারো
              ->orWhereIn('status', [21, 22]);
    })
    ->count();


        $data['total_partial_delivery_complete'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?) and payment_type = ?', [25, 2, 5])
            ->count();
        $data['today_total_partial_delivery_complete'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?) and payment_type = ?', [25, 2, 5])
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();

        $data['today_total_pending_delivery'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status > 11 and delivery_type in (?)', [3])
            ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
            ->count();
        $data['total_pending_delivery'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status > 11 and delivery_type in (?)', [3])
            ->count();

        $data['total_return_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4])
            ->count();

        $data['total_return_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
            // ->whereRaw('status = ? and delivery_type in (?,?)', [36,2,4])
            ->whereRaw('status = ? and delivery_type in (?)', [36, 4])
            ->count();

        $data['total_pending_collect_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 4])
            ->sum('merchant_paid_amount');

        // $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
        //                                             ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
        //                                             ->toSql();

        // dd($merchant_id, $data['total_pending_collect_amount'] );


        $data['total_collect_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5])
            ->sum('merchant_paid_amount');

        return $data;
    }


    public function merchantDashboardCounterEvent($merchant_id)
    {
        // $counter_data = $this->returnDashboardCounterForMerchant($merchant_id);
        // event(new MerchantDashboardRealTimeCounter(json_decode($counter_data), $merchant_id));
        // return "Merchant dashboard counter";

    }


    protected function callAPI2($userPwd, $headers = array(), $method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $userPwd); //Your credentials goes here

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }
    protected function callAPI($userPwd, $headers, $method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $userPwd); //Your credentials goes here

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }


    protected function callPaperFlyAPI($data)
    {

        $method = "POST";
        // $api_url                 = "https://sandbox.paperflybd.com/OrderPlacement";
        // $authorizationUserPWD    = "m117216:abcd1234";

        $api_url = "https://paperflybd.com/OrderPlacement";
        $authorizationUserPWD = "m117216:abcd1234";

        $headers = [
            'paperflykey: Paperfly_~La?Rj73FcLm'
        ];


        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $api_url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $authorizationUserPWD); //Your credentials goes here

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }


}

<?php
namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\StaffPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;

class AccountController extends Controller
{
    public function receipt_payment(){
        $data                   = [];
        $data['p_main_menu']    = 'accounts';
        $data['main_menu']      = 'expenses';
        $data['child_menu']     = 'receipt_payment';
        $data['page_title']     = 'Receipt & Payment';
        return view('admin.account.report.receipt_payment', $data);
    }

    public function select_receipt_payment(Request $request){
        
        $data                   = [];
        $year_month = $request->month;
        
        $month = date('m',strtotime($year_month.'-01'));
        $year = date('Y',strtotime($year_month.'-01'));

        $date = $year.'-'.$month.'-01';

        $income = Expense::whereDate('date','<',$date)->where(['type'=>2,'status'=>1])->sum('amount');
        $expense = Expense::whereDate('date','<',$date)->where(['type'=>1,'status'=>1])->sum('amount');
        $receiveds = ParcelDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('amount');
        $marchent_payments = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('paid_amount');       
        $salaries = StaffPayment::whereDate('payment_date','<',$date)->sum('paid_amount');

        $delivery_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('delivery_charge');
        $weight_package_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('weight_package_charge');
        $cod_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('cod_charge');
        $return_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('return_charge');

        $data['opening_balance'] = $income+$receiveds+$delivery_charge+$weight_package_charge+$cod_charge+$return_charge-$expense-$marchent_payments-$salaries;

        $data['incomes']    = Expense::with(['expense_heads'])->whereMonth('date',$month)->whereYear('date',$year)->where(['type'=>2,'status'=>1])->get();
        $data['expenses']   = Expense::with(['expense_heads'])->whereMonth('date',$month)->whereYear('date',$year)->where(['type'=>1,'status'=>1])->get();
        $data['receiveds']   = ParcelDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->get();
        $data['marchent_payments'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->get();

        $data['salaries'] = StaffPayment::with(['staff'])->whereMonth('payment_date',$month)->whereYear('payment_date',$year)->get();

        $data['delivery_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('delivery_charge');
        $data['weight_package_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('weight_package_charge');
        $data['cod_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('cod_charge');
        $data['return_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('return_charge');
        
        $data['month']      = $year.'-'.$month;
        
        return view('admin.account.report.receipt_payment_details', $data);
    }


    public function income_statement(){
        $data                   = [];
        $data['p_main_menu']    = 'accounts';
        $data['main_menu']      = 'expenses';
        $data['child_menu']     = 'income_statement';
        $data['page_title']     = 'Income Statement';
        return view('admin.account.report.income_statement', $data);
    }

    public function select_income_statement(Request $request){
        
        $data                   = [];
        $year_month = $request->month;
        
        $month = date('m',strtotime($year_month.'-01'));
        $year = date('Y',strtotime($year_month.'-01'));

        $date = $year.'-'.$month.'-01';

        $income = Expense::whereDate('date','<',$date)->where(['type'=>2,'status'=>1])->sum('amount');
        $expense = Expense::whereDate('date','<',$date)->where(['type'=>1,'status'=>1])->sum('amount');
        $receiveds = ParcelDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('amount');
        $marchent_payments = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('paid_amount');       
        $salaries = StaffPayment::whereDate('payment_date','<',$date)->sum('paid_amount');

        $delivery_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('delivery_charge');
        $weight_package_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('weight_package_charge');
        $cod_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('cod_charge');
        $return_charge = ParcelMerchantDeliveryPaymentDetail::whereDate('date_time','<',$date)->where(['status'=>2])->sum('return_charge');

        $data['opening_balance'] = $income+$receiveds+$delivery_charge+$weight_package_charge+$cod_charge+$return_charge-$expense-$marchent_payments-$salaries;

        $data['incomes']    = Expense::with(['expense_heads'])->whereMonth('date',$month)->whereYear('date',$year)->where(['type'=>2,'status'=>1])->get();
        $data['expenses']   = Expense::with(['expense_heads'])->whereMonth('date',$month)->whereYear('date',$year)->where(['type'=>1,'status'=>1])->get();
        $data['receiveds']   = ParcelDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->get();
        $data['marchent_payments'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->get();

        $data['salaries'] = StaffPayment::with(['staff'])->whereMonth('payment_date',$month)->whereYear('payment_date',$year)->get();

        $data['delivery_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('delivery_charge');
        $data['weight_package_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('weight_package_charge');
        $data['cod_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('cod_charge');
        $data['return_charge'] = ParcelMerchantDeliveryPaymentDetail::with(['parcel'])->whereMonth('date_time',$month)->whereYear('date_time',$year)->where(['status'=>2])->sum('return_charge');
        
        $data['month']      = $year.'-'.$month;
        
        return view('admin.account.report.income_statement_details', $data);
    }

    
}

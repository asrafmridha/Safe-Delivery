<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\InternalTransaction;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        //Your total pending
      /*  $data['yourPendingTotalDebit'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->where('transaction_type', "debit")->sum("bdt_amount");
        $data['yourPendingTotalCredit'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->where('transaction_type', "credit")->sum("bdt_amount");
        $data['yourPendingTotalTransaction'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->sum("bdt_amount");

        // your today pending
        $data['yourTodayPendingTotalDebit'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->where('transaction_type', "debit")->whereRaw('date = ?', [date("Y-m-d")])->sum("bdt_amount");
        $data['yourTodayPendingTotalCredit'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->where('transaction_type', "credit")->whereRaw('date = ?', [date("Y-m-d")])->sum("bdt_amount");
        $data['yourTodayPendingTotalTransaction'] = Transaction::where('created_by', auth()->user()->id)->where('status', 0)->whereRaw('date = ?', [date("Y-m-d")])->sum("bdt_amount");

        // your overview
        $data['yourTotalDebit'] = Transaction::where('created_by', auth()->user()->id)->where('status', "!=", 2)->where('transaction_type', "debit")->sum("bdt_amount");
        $data['yourTotalCredit'] = Transaction::where('created_by', auth()->user()->id)->where('status', "!=", 2)->where('transaction_type', "credit")->sum("bdt_amount");
        $data['yourTotalTransaction'] = Transaction::where('created_by', auth()->user()->id)->sum("bdt_amount");
        $data['yourRejectedTransaction'] = Transaction::where('created_by', auth()->user()->id)->where('status', 2)->count();

        //Overview
        $data['totalApprovedTransaction'] = Transaction::where('status', 1)->sum("bdt_amount");
        $data['totalApprovedCredit'] = Transaction::where('status', 1)->where('transaction_type', "credit")->sum("bdt_amount");
        $data['totalApprovedDebit'] = Transaction::where('status', 1)->where('transaction_type', "debit")->sum("bdt_amount");

        //Overview - Today
        $data['todayApprovedTransaction'] = Transaction::where('status', 1)->whereRaw('date = ?', [date("Y-m-d")])->sum("bdt_amount");
        $data['todayApprovedCredit'] = Transaction::where('status', 1)->whereRaw('date = ?', [date("Y-m-d")])->where('transaction_type', "credit")->sum("bdt_amount");
        $data['todayApprovedDebit'] = Transaction::where('status', 1)->whereRaw('date = ?', [date("Y-m-d")])->where('transaction_type', "debit")->sum("bdt_amount");

        //Overview - total pending
        $data['pendingTodayDebit'] = Transaction::where('status', 0)->whereRaw('date = ?', [date("Y-m-d")])->where('transaction_type', "debit")->sum("bdt_amount");
        $data['pendingTodayCredit'] = Transaction::where('status', 0)->whereRaw('date = ?', [date("Y-m-d")])->where('transaction_type', "credit")->sum("bdt_amount");
        $data['pendingTodayTransaction'] = Transaction::where('status', 0)->whereRaw('date = ?', [date("Y-m-d")])->sum("bdt_amount");

        //table

        $data['clientTransactions'] = Transaction::where('transaction_for', "client")->orderBy('id', "desc")->take(4)->get();
        $data['countClientTransaction'] = Transaction::where('transaction_for', "client")->count();

        $data['internalTransactions'] = Transaction::where('transaction_for', "internal")->orderBy('id', "desc")->take(4)->get();
        $data['countInternalTransaction'] = Transaction::where('transaction_for', "internal")->count();

        $data['otherTransactions'] = Transaction::where('transaction_for', "other")->orderBy('id', "desc")->take(4)->get();
        $data['countOtherTransaction'] = Transaction::where('transaction_for', "other")->count();


        $data['currencies'] = DB::table('transactions')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select([
                'currency_id',
                'currencies.name',
                'currencies.code',
                DB::raw('sum(amount) as total'),
                DB::raw("SUM( IF(transaction_type = 'debit', amount, 0)) as debit "),
                DB::raw("SUM( IF(transaction_type = 'credit' and user_id != '' , amount, 0)) as internal_debit "),
                DB::raw("SUM( IF(transaction_type = 'debit' and user_id != '' , amount, 0)) as internal_credit "),
                DB::raw("SUM( IF(transaction_type = 'credit', amount, 0)) as credit "),
            ])
            ->whereIn('status', [1, 4])
            ->groupBy('currency_id')
            ->get();
        $data['clients'] = DB::table('transactions')
            ->join('clients', 'clients.id', '=', 'transactions.client_id')
            ->select([
                'client_id',
                'clients.name',
                'clients.phone',
                DB::raw("SUM( IF(transaction_type = 'debit', bdt_amount, 0)) as debit "),
                DB::raw("SUM( IF(transaction_type = 'credit', bdt_amount, 0)) as credit "),
            ])
            ->whereIn('transactions.status', [1, 3, 4])
            ->groupBy('client_id')
            ->orderBy('transactions.id',"desc")
            ->take(4)->get();
        $data['users'] = User::orderBy('id', "desc")->withCount(['transaction as credit' => function ($query) {
            $query->select(DB::raw('SUM(bdt_amount)'))
                ->where('transaction_type', "credit")
                ->whereIn('status', [1, 4]);
        }
        ])->withCount(['transaction as debit' => function ($query) {
            $query->select(DB::raw('SUM(bdt_amount)'))
                ->where('transaction_type', "debit")
                ->whereIn('status', [1, 4]);
        }
        ])->withCount(['internal_transaction as internal_debit' => function ($query) {
            $query->select(DB::raw('SUM(bdt_amount)'))
                ->where('transaction_type', "credit")
                ->whereIn('status', [1, 4]);
        }
        ])->withCount(['internal_transaction as internal_credit' => function ($query) {
            $query->select(DB::raw('SUM(bdt_amount)'))
                ->where('transaction_type', "debit")
                ->whereIn('status', [1, 4]);
        }
        ])->get();*/

        /*$data['clients'] = DB::table('transactions')
            ->join('clients', 'clients.id', '=', 'transactions.client_id')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select([
                'client_id',
                'clients.name',
                'clients.phone',
                DB::raw("currencies.name as currency_name "),
                DB::raw("currencies.code as currency_code "),
                DB::raw("SUM( IF(transaction_type = 'debit', bdt_amount, 0)) as debit "),
                DB::raw("SUM( IF(transaction_type = 'credit', bdt_amount, 0)) as credit "),
            ])
            ->groupBy('client_id')
            ->groupBy('currency_id')
            ->get();*/


//        dd($data);
        return view('backend.dashboard', $data);
    }

    public function getDashboardCounter()
    {
        $data['totalPending'] = Transaction::where('status', 0)->count();
        $data['totalPendingPayment'] = Payment::where('status', 0)->count();
        $data['totalPendingExpense'] = Expense::where('status', 0)->count();
        $data['totalPendingInternalTransaction'] = InternalTransaction::where('status', 0)->count();
        $data['finalPending'] =  $data['totalPending'] + $data['totalPendingPayment'] + $data['totalPendingExpense'] + $data['totalPendingInternalTransaction']  ;

        $data['totalOrder'] = Transaction::where('status', 3)->count();
        $data['totalApproved'] = Transaction::where('status', 1)->count();
        $data['totalRejected'] = Transaction::where('status', 2)->count();
        return $data;
    }

    public function getOrderNotification()
    {
        $orders=[];
        if(auth()->user()->user_role=="super_admin"){
            $orders = Transaction::where('status', 3)->orderBy('updated_at','desc')->get();
        } elseif (check_access("transaction list")) {
//            $orders = Transaction::where('created_by', auth()->user()->id)->where('status', 3)->orderBy('updated_at','desc')->get();
            $orders = Transaction::where('status', 3)->orderBy('updated_at','desc')->get();
        }
        return view("backend.load.order_notification",compact('orders'));
//        return view("backend.load.old_order_notification",compact('orders'));
    }
    public function getApprovedNotification()
    {
        $orders=[];
        if(auth()->user()->user_role=="super_admin"){
            $orders = Transaction::where('status', 1)->orderBy('updated_at','desc')->get();
        } elseif (check_access("transaction list")) {
//            $orders = Transaction::where('created_by', auth()->user()->id)->where('status', 3)->orderBy('updated_at','desc')->get();
            $orders = Transaction::where('status', 1)->orderBy('updated_at','desc')->get();
        }
        return view("backend.load.approved_notification",compact('orders'));
//        return view("backend.load.old_approved_notification",compact('orders'));
    }
    public function getPendingNotification()
    {
        $orders=[];
        if(auth()->user()->user_role=="super_admin"){
            $orders = Transaction::where('status', 0)->orderBy('updated_at','desc')->get();
        } elseif (check_access("transaction list")) {
//            $orders = Transaction::where('created_by', auth()->user()->id)->where('status', 3)->orderBy('updated_at','desc')->get();
            $orders = Transaction::where('status', 0)->orderBy('updated_at','desc')->get();
        }
        return view("backend.load.pending_notification",compact('orders'));
//        return view("backend.load.old_pending_notification",compact('orders'));
    }


    public function login()
    {
        return view('backend.login');
    }

    public function doLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                Session::flash('message', "Invalid Data given!");
                Session::flash('alert', 'danger');
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $creds = $request->except('_token');
            if (auth()->attempt($creds)) {
                Toastr::success("Login Successful!");
                return redirect()->route('dashboard');
            }
            Session::flash('message', "Invalid Email or Password!");
            Session::flash('alert', 'danger');
            return redirect()->back();
        } catch (\Exception $exception) {
            Session::flash('message', $exception->getMessage());
            Session::flash('alert', 'danger');
            return redirect()->back();
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function payment(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');

        $filter = [];
        $status = $request->input('status');
        $payment_method_id = $request->input('payment_method_id');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('payment_method_id') && !is_null($payment_method_id)) {
            $model->where('payment_method_id', $payment_method_id);
            $filter['payment_method_id'] = $request->get('payment_method_id');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }

        $payments = $model->orderBy('id', 'desc')->get();
        $clients = Client::all();
        $methods = PaymentMethod::all();
        return view('backend.reports.payment', compact('payments', 'clients', 'filter', 'methods'));
    }

    public function paymentPrint(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');

        $filter = [];
        $status = $request->input('status');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }

        $payments = $model->orderBy('id', 'desc')->get();
//        $clients = Client::all();
        return view('backend.reports.payment_print', compact('payments', 'filter'));
    }

    public function paymentPdf(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');

        $filter = [];
        $status = $request->input('status');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }

        $payments = $model->orderBy('id', 'desc')->get();
        $customPaper = array(0, 0, 565.65, 800);
        $pdf = PDF::loadView('backend.reports.payment_pdf', compact('payments', 'filter'))->setPaper($customPaper, 'landscape');
//         return $pdf->download('client_ledger.pdf');
        $path = public_path('pdf/');
        $fileName = "report_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }

    public function transaction(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $items = [];
        $status = $request->input('status');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $items = $model->orderBy('id', 'desc')->get();
        $currencies = Currency::all();
        $clients = Client::all();
        return view('backend.reports.transaction', compact('items', 'currencies', 'filter', 'clients'));
    }

    public function transactionPrint(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $items = [];
        $status = $request->input('status');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $items = $model->orderBy('id', 'desc')->get();
//        $currencies = Currency::all();
//        $clients = Client::all();
        return view('backend.reports.transaction_print', compact('items', 'filter'));
    }

    public function transactionPdf(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $items = [];
        $status = $request->input('status');
        $client_id = $request->input('client_id');
        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d");
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->whereRaw('status in (' . $status . ')');
            $filter['status'] = $request->get('status');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('supplier_id') && !is_null($supplier_id)) {
            $model->whereRaw('supplier_id in (' . $supplier_id . ')');
            $filter['supplier_id'] = $request->get('supplier_id');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $items = $model->orderBy('id', 'desc')->get();
        $customPaper = array(0, 0, 565.65, 800);
        $pdf = PDF::loadView('backend.reports.transaction_pdf', compact('items', 'filter'))->setPaper($customPaper, 'landscape');
//         return $pdf->download('client_ledger.pdf');
        $path = public_path('pdf/');
        $fileName = "report_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }

    public function profit(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
//        $status = $request->input('status');
//        $client_id = $request->input('client_id');
//        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
//        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        /* if ($request->has('status') && !is_null($status)) {
             $model->whereRaw('status in (' . $status . ')');
             $filter['status'] = $request->get('status');
         }
         if ($request->has('client_id') && !is_null($client_id)) {
             $model->whereRaw('client_id in (' . $client_id . ')');
             $filter['client_id'] = $request->get('client_id');
         }
         if ($request->has('supplier_id') && !is_null($supplier_id)) {
             $model->whereRaw('supplier_id in (' . $supplier_id . ')');
             $filter['supplier_id'] = $request->get('supplier_id');
         }
         if ($request->has('currency_id') && !is_null($currency_id)) {
             $model->whereRaw('currency_id in (' . $currency_id . ')');
             $filter['currency_id'] = $request->get('currency_id');
         }*/

//        $items = $model->orderBy('id', 'desc')->get();
        $model->whereIn('status', [1, 4]);
        $total_transaction = $model->count();
        $total_profit = $model->sum('profit');
        $total_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_s_bdt_amount = $model->sum('s_bdt_amount');
        $currencies = $model->with('currency')->select('currency_id',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('count(*) as total'), DB::raw('SUM(b_bdt_amount) as total_b_bdt_amount'),
            DB::raw('SUM(s_bdt_amount) as total_s_bdt_amount'))
            ->groupBy('currency_id')
            ->get();
//        dd($currencies);
//        dd($total_s_bdt_amount);
        return view('backend.reports.profit', compact('filter', 'currencies', 'total_profit', 'total_b_bdt_amount', 'total_s_bdt_amount', 'total_transaction'));
    }

    public function profitPrint(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
//        $status = $request->input('status');
//        $client_id = $request->input('client_id');
//        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
//        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        /* if ($request->has('status') && !is_null($status)) {
             $model->whereRaw('status in (' . $status . ')');
             $filter['status'] = $request->get('status');
         }
         if ($request->has('client_id') && !is_null($client_id)) {
             $model->whereRaw('client_id in (' . $client_id . ')');
             $filter['client_id'] = $request->get('client_id');
         }
         if ($request->has('supplier_id') && !is_null($supplier_id)) {
             $model->whereRaw('supplier_id in (' . $supplier_id . ')');
             $filter['supplier_id'] = $request->get('supplier_id');
         }
         if ($request->has('currency_id') && !is_null($currency_id)) {
             $model->whereRaw('currency_id in (' . $currency_id . ')');
             $filter['currency_id'] = $request->get('currency_id');
         }*/

//        $items = $model->orderBy('id', 'desc')->get();
        $model->whereIn('status', [1, 4]);
        $total_transaction = $model->count();
        $total_profit = $model->sum('profit');
        $total_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_s_bdt_amount = $model->sum('s_bdt_amount');
        $currencies = $model->with('currency')->select('currency_id',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('count(*) as total'), DB::raw('SUM(b_bdt_amount) as total_b_bdt_amount'),
            DB::raw('SUM(s_bdt_amount) as total_s_bdt_amount'))
            ->groupBy('currency_id')
            ->get();
//        dd($total_s_bdt_amount);
        return view('backend.reports.profit_print', compact('filter', 'currencies','total_profit', 'total_b_bdt_amount', 'total_s_bdt_amount', 'total_transaction'));

    }

    public function profitPdf(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
//        $status = $request->input('status');
//        $client_id = $request->input('client_id');
//        $supplier_id = $request->input('supplier_id');
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
//        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        /* if ($request->has('status') && !is_null($status)) {
             $model->whereRaw('status in (' . $status . ')');
             $filter['status'] = $request->get('status');
         }
         if ($request->has('client_id') && !is_null($client_id)) {
             $model->whereRaw('client_id in (' . $client_id . ')');
             $filter['client_id'] = $request->get('client_id');
         }
         if ($request->has('supplier_id') && !is_null($supplier_id)) {
             $model->whereRaw('supplier_id in (' . $supplier_id . ')');
             $filter['supplier_id'] = $request->get('supplier_id');
         }
         if ($request->has('currency_id') && !is_null($currency_id)) {
             $model->whereRaw('currency_id in (' . $currency_id . ')');
             $filter['currency_id'] = $request->get('currency_id');
         }*/

//        $items = $model->orderBy('id', 'desc')->get();
        $model->whereIn('status', [1, 4]);
        $total_transaction = $model->count();
        $total_profit = $model->sum('profit');
        $total_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_s_bdt_amount = $model->sum('s_bdt_amount');
        $currencies = $model->with('currency')->select('currency_id',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('count(*) as total'), DB::raw('SUM(b_bdt_amount) as total_b_bdt_amount'),
            DB::raw('SUM(s_bdt_amount) as total_s_bdt_amount'))
            ->groupBy('currency_id')
            ->get();

        $pdf = PDF::loadView('backend.reports.profit_pdf', compact('filter', 'currencies', 'total_profit', 'total_b_bdt_amount', 'total_s_bdt_amount', 'total_transaction'));

        $path = public_path('pdf/');
        $fileName = "report_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }

    public function currency(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::select();
        $newModel = Transaction::select();
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $newModel->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $newModel->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $newModel->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        //pending
        $model->where('status', 0);
        $total_pending_transaction = $model->count();
        $total_pending_amount = $model->sum('amount');
        $total_pending_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_pending_s_bdt_amount = $model->sum('s_bdt_amount');

        //approved / completed
        $newModel->whereIn('status', [1, 4]);
        $total_approved_transaction = $newModel->count();
        $total_approved_amount = $newModel->sum('amount');
        $total_approved_b_bdt_amount = $newModel->sum('b_bdt_amount');
        $total_approved_s_bdt_amount = $newModel->sum('s_bdt_amount');


        $currencies = Currency::all();
        return view('backend.reports.currency', compact('currencies', 'filter',
            'total_pending_b_bdt_amount', 'total_pending_s_bdt_amount', 'total_pending_transaction',
            'total_approved_b_bdt_amount', 'total_approved_s_bdt_amount', 'total_approved_transaction',
            'total_approved_amount', 'total_pending_amount'));
    }

    public function currencyPrint(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::select();
        $newModel = Transaction::select();
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $newModel->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $newModel->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $newModel->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        //pending
        $model->where('status', 0);
        $total_pending_transaction = $model->count();
        $total_pending_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_pending_s_bdt_amount = $model->sum('s_bdt_amount');

        //approved / completed
        $newModel->whereIn('status', [1, 4]);
        $total_approved_transaction = $newModel->count();
        $total_approved_b_bdt_amount = $newModel->sum('b_bdt_amount');
        $total_approved_s_bdt_amount = $newModel->sum('s_bdt_amount');

        return view('backend.reports.currency_print', compact('filter',
            'total_pending_b_bdt_amount', 'total_pending_s_bdt_amount', 'total_pending_transaction',
            'total_approved_b_bdt_amount', 'total_approved_s_bdt_amount', 'total_approved_transaction'));
    }

    public function currencyPdf(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::select();
        $newModel = Transaction::select();
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $currency_id = $request->input('currency_id');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $newModel->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $newModel->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $newModel->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        //pending
        $model->where('status', 0);
        $total_pending_transaction = $model->count();
        $total_pending_b_bdt_amount = $model->sum('b_bdt_amount');
        $total_pending_s_bdt_amount = $model->sum('s_bdt_amount');

        //approved / completed
        $newModel->whereIn('status', [1, 4]);
        $total_approved_transaction = $newModel->count();
        $total_approved_b_bdt_amount = $newModel->sum('b_bdt_amount');
        $total_approved_s_bdt_amount = $newModel->sum('s_bdt_amount');

        $pdf = PDF::loadView('backend.reports.currency_pdf', compact('filter',
            'total_pending_b_bdt_amount', 'total_pending_s_bdt_amount', 'total_pending_transaction',
            'total_approved_b_bdt_amount', 'total_approved_s_bdt_amount', 'total_approved_transaction'));
//         return $pdf->download('client_ledger.pdf');
        $path = public_path('pdf/');
        $fileName = "report_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }

    public function expense(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Expense::select()->with('expense_head');
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $expense_head_id = $request->input('expense_head_id');
        $status = $request->input('status');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('expense_head_id') && !is_null($expense_head_id)) {
            $model->whereRaw('expense_head_id in (' . $expense_head_id . ')');
            $filter['expense_head_id'] = $request->get('expense_head_id');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->where('status', $status);
            $filter['status'] = $request->get('status');
        }
//        $model->where('status',0);
        $items = $model->get();
        $expense_heads = ExpenseHead::all();
        return view('backend.reports.expense', compact('expense_heads', 'filter', 'items'));
    }

    public function expensePrint(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Expense::select()->with('expense_head');
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $expense_head_id = $request->input('expense_head_id');
        $status = $request->input('status');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('expense_head_id') && !is_null($expense_head_id)) {
            $model->whereRaw('expense_head_id in (' . $expense_head_id . ')');
            $filter['expense_head_id'] = $request->get('expense_head_id');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->where('status', $status);
            $filter['status'] = $request->get('status');
        }
//        $model->where('status',0);
        $items = $model->get();
        return view('backend.reports.expense_print', compact('filter', 'items'));
    }

    public function expensePdf(Request $request)
    {
        if (!check_access("report")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Expense::select()->with('expense_head');
        $filter = [];
        $from_date = $request->input('from_date') ?? date("Y-m-d", strtotime(date("Y-m") . "-1"));
        $expense_head_id = $request->input('expense_head_id');
        $status = $request->input('status');

        if (!is_null($from_date) && $from_date != 0) {
            $model->whereDate('date', '>=', $from_date);
            $filter['from_date'] = $from_date;
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('expense_head_id') && !is_null($expense_head_id)) {
            $model->whereRaw('expense_head_id in (' . $expense_head_id . ')');
            $filter['expense_head_id'] = $request->get('expense_head_id');
        }
        if ($request->has('status') && !is_null($status)) {
            $model->where('status', $status);
            $filter['status'] = $request->get('status');
        }
        $items = $model->get();

        $pdf = PDF::loadView('backend.reports.expense_pdf', compact('filter', 'items'));
//         return $pdf->download('client_ledger.pdf');
        $path = public_path('pdf/');
        $fileName = "report_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }
}

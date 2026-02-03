<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function client(Request $request)
    {
        if (!check_access("ledger client")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $from_date = $request->get('from_date') ?? date('Y-m') . "-01";
        $to_date = $request->get('to_date') ?? date('Y-m-d');
        $order_by = $request->get('order_by') ?? 'asc';

        $model = Transaction::with('client', 'currency', 'supplier')->whereIn('status', [1, 3, 4]);
        $payment = Payment::with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        $previous_balance = 0;
        if ($request->has('client_id') && !is_null($client_id)) {

            $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('b_bdt_amount');
            $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('s_bdt_amount');

            $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $previous_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;

            $model->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $payment->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $filter['client_id'] = $request->get('client_id');

            if ($from_date) {
                $model->whereDate('date', '>=', $from_date);
                $payment->whereDate('date', '>=', $from_date);
                $filter['from_date'] = $from_date;
            }
            if ($to_date) {
                $model->whereDate('date', '<=', $to_date);
                $payment->whereDate('date', '<=', $to_date);
                $filter['to_date'] = $to_date;
            }
            $items = $model->get()->toArray();
            $payments = $payment->whereIn('status', [1])->get()->toArray();

        }
        $items = array_merge($items, $payments);
        $desc_final_balance=0;
        if ($order_by == 'desc') {
            usort($items, function ($a, $b) {
                return new DateTime($b['created_at']) <=> new DateTime($a['created_at']);
            });
            if ($request->has('client_id') && !is_null($client_id)) {
                $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->sum('b_bdt_amount');
                $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->sum('s_bdt_amount');

                $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->sum('amount');
                $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->sum('amount');
                $desc_final_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;
            }
        } else {
            usort($items, function ($a, $b) {
                return new DateTime($a['created_at']) <=> new DateTime($b['created_at']);
            });
        }
        $filter['order_by'] = $order_by;


//        dd($desc_final_balance);

        $clients = Client::all();
        return view('backend.ledgers.index', compact('items','desc_final_balance', 'previous_balance', 'clients', 'filter', 'payments'));
    }

    public function clientPrint(Request $request)
    {
        if (!check_access("ledger client")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $from_date = $request->get('from_date') ?? date('Y-m') . "-01";
        $to_date = $request->get('to_date') ?? date('Y-m-d');
        $order_by = $request->get('order_by') ?? 'asc';

        $model = Transaction::with('client', 'currency', 'supplier')->whereIn('status', [1, 3, 4]);
        $payment = Payment::with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        $previous_balance = 0;
        if ($request->has('client_id') && !is_null($client_id)) {

            $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('b_bdt_amount');
            $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('s_bdt_amount');

            $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $previous_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;

            $model->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $payment->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $filter['client_id'] = $request->get('client_id');

            if ($from_date) {
                $model->whereDate('date', '>=', $from_date);
                $payment->whereDate('date', '>=', $from_date);
                $filter['from_date'] = $from_date;
            }
            if ($to_date) {
                $model->whereDate('date', '<=', $to_date);
                $payment->whereDate('date', '<=', $to_date);
                $filter['to_date'] = $to_date;
            }
            $items = $model->get()->toArray();
            $payments = $payment->whereIn('status', [1])->get()->toArray();

        }
        $items = array_merge($items, $payments);
        $desc_final_balance=0;
        if ($order_by == 'desc') {
            usort($items, function ($a, $b) {
                return new DateTime($b['created_at']) <=> new DateTime($a['created_at']);
            });
            if ($request->has('client_id') && !is_null($client_id)) {
                $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->sum('b_bdt_amount');
                $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->sum('s_bdt_amount');

                $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->sum('amount');
                $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->sum('amount');
                $desc_final_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;
            }
        } else {
            usort($items, function ($a, $b) {
                return new DateTime($a['created_at']) <=> new DateTime($b['created_at']);
            });
        }
        $filter['order_by'] = $order_by;


//        dd($desc_final_balance);

        $clients = Client::all();
        return view('backend.ledgers.print', compact('items', 'desc_final_balance', 'previous_balance', 'clients', 'filter', 'payments'));
    }

    public function clientPdf(Request $request)
    {
        if (!check_access("ledger client")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $from_date = $request->get('from_date') ?? date('Y-m') . "-01";
        $to_date = $request->get('to_date') ?? date('Y-m-d');
        $order_by = $request->get('order_by') ?? 'asc';

        $model = Transaction::with('client', 'currency', 'supplier')->whereIn('status', [1, 3, 4]);
        $payment = Payment::with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        $previous_balance = 0;
        if ($request->has('client_id') && !is_null($client_id)) {

            $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('b_bdt_amount');
            $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->whereDate('date', '<', $from_date)->sum('s_bdt_amount');

            $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->whereDate('date', '<', $from_date)->sum('amount');
            $previous_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;

            $model->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $payment->where(function ($query) use ($client_id) {
                $query->where('client_id', $client_id)
                    ->orWhere('supplier_id', $client_id);
            });
            $filter['client_id'] = $request->get('client_id');

            if ($from_date) {
                $model->whereDate('date', '>=', $from_date);
                $payment->whereDate('date', '>=', $from_date);
                $filter['from_date'] = $from_date;
            }
            if ($to_date) {
                $model->whereDate('date', '<=', $to_date);
                $payment->whereDate('date', '<=', $to_date);
                $filter['to_date'] = $to_date;
            }
            $items = $model->get()->toArray();
            $payments = $payment->whereIn('status', [1])->get()->toArray();

        }
        $items = array_merge($items, $payments);
        $desc_final_balance=0;
        if ($order_by == 'desc') {
            usort($items, function ($a, $b) {
                return new DateTime($b['created_at']) <=> new DateTime($a['created_at']);
            });
            if ($request->has('client_id') && !is_null($client_id)) {
                $supply_bdt_amount = Transaction::where('supplier_id', $client_id)->whereIn('status', [1, 3, 4])->sum('b_bdt_amount');
                $buy_bdt_amount = Transaction::where('client_id', $client_id)->whereIn('status', [1, 3, 4])->sum('s_bdt_amount');

                $supply_payment_amount = Payment::where('supplier_id', $client_id)->whereIn('status', [1])->sum('amount');
                $client_payment_amount = Payment::where('client_id', $client_id)->whereIn('status', [1])->sum('amount');
                $desc_final_balance = $supply_bdt_amount - $buy_bdt_amount - $supply_payment_amount + $client_payment_amount;
            }
        } else {
            usort($items, function ($a, $b) {
                return new DateTime($a['created_at']) <=> new DateTime($b['created_at']);
            });
        }
        $filter['order_by'] = $order_by;


//        dd($desc_final_balance);

        $clients = Client::all();
        $pdf = PDF::loadView('backend.ledgers.pdf', compact('items', 'desc_final_balance', 'previous_balance', 'clients', 'filter', 'payments'));
//         return $pdf->download('client_ledger.pdf');
        $pdf->setPaper('A4', 'landscape');
        $path = public_path('pdf/');
        $fileName = "ledger_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }

    public function supplier(Request $request)
    {
        if (!check_access("ledger supplier")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->whereIn('status', [1, 3, 4])
            ->select();
        $payment = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $payment->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $payment->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('supplier_id in (' . $client_id . ')');
            $payment->whereRaw('supplier_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
            if ($request->has('order_by')) {
                $filter['order_by'] = $request->get('order_by');
                if ($request->input('order_by') == "asc") {
                    $items = $model->get();
                    $payments = $payment->get();
                } else {
                    $items = $model->orderBy("id", "desc")->get();
                    $payments = $payment->orderBy("id", "desc")->get();
                }
            } else {
                $items = $model->orderBy("id", "desc")->get();
                $payments = $payment->orderBy("id", "desc")->get();
            }
        }
        $clients = Client::all();
//        dd($payments);
        return view('backend.ledgers.supplier', compact('items', 'clients', 'filter', 'payments'));
    }

    public function supplierPrint(Request $request)
    {
        if (!check_access("ledger supplier")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->whereIn('status', [1, 3, 4])
            ->select();
        $payment = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $payment->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $payment->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('supplier_id in (' . $client_id . ')');
            $payment->whereRaw('supplier_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
            if ($request->has('order_by')) {
                $filter['order_by'] = $request->get('order_by');
                if ($request->input('order_by') == "asc") {
                    $items = $model->get();
                    $payments = $payment->get();
                } else {
                    $items = $model->orderBy("id", "desc")->get();
                    $payments = $payment->orderBy("id", "desc")->get();
                }
            } else {
                $items = $model->orderBy("id", "desc")->get();
                $payments = $payment->orderBy("id", "desc")->get();
            }
        }
        $clients = Client::all();
        return view('backend.ledgers.supplier_print', compact('items', 'clients', 'filter', 'payments'));
    }

    public function supplierPdf(Request $request)
    {
        if (!check_access("ledger supplier")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->whereIn('status', [1, 3, 4])
            ->select();
        $payment = Payment::orderBy('id', 'desc')->with('client', 'created_user', 'payment_method', 'supplier');
        $filter = [];
        $items = [];
        $payments = [];
        $client_id = $request->input('client_id');
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $payment->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $payment->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('supplier_id in (' . $client_id . ')');
            $payment->whereRaw('supplier_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
            if ($request->has('order_by')) {
                $filter['order_by'] = $request->get('order_by');
                if ($request->input('order_by') == "asc") {
                    $items = $model->get();
                    $payments = $payment->get();
                } else {
                    $items = $model->orderBy("id", "desc")->get();
                    $payments = $payment->orderBy("id", "desc")->get();
                }
            } else {
                $items = $model->orderBy("id", "desc")->get();
                $payments = $payment->orderBy("id", "desc")->get();
            }
        }
        $clients = Client::all();
        $pdf = PDF::loadView('backend.ledgers.supplier_print', compact('items', 'clients', 'filter', 'payments'));
//         return $pdf->download('client_ledger.pdf');
        $path = public_path('pdf/');
        $fileName = "ledger_supplier_" . time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf = public_path('pdf/' . $fileName);

        return response()->download($pdf);

    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MyTransactionController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("transaction my-list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Transaction::orderBy('id', 'desc')->where('created_by', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->with('client', 'currency',"user","created_user")->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('transaction view')) {
                    $btn .= '<button class="btn btn-info btn-sm view-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="View transaction"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit')) {
                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('transaction delete')) {
//                    $btn .= " <a href='" . route("transaction.delete", $row->id) . "' class='btn btn-danger btn-sm' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount,2);
            })

            ->addColumn('bdt_amount', function ($row) {
                return number_format($row->bdt_amount,2);
            })
            ->addColumn('status', function ($row) {
                $btn = '';
                if ($row->status == 1) {
                    $btn .= '<p class="badge badge-info">Approved</p>';
                } elseif ($row->status == 0) {
                    $btn .= '<p class="badge badge-warning">Pending</p>';
                } elseif ($row->status == 2) {
                    $btn .= '<p class="badge badge-danger">Rejected</p>';
                } elseif ($row->status == 3) {
                    $btn .= '<p class="badge badge-primary">Order</p>';
                } elseif ($row->status == 4) {
                    $btn .= '<p class="badge badge-success">Completed</p>';
                }
                if (check_permission('transaction edit')) {
                    $btn .= '<button class="btn btn-primary btn-sm mt-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("transaction my-list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        return view('backend.transactions.my_transactions.index', compact('users', 'currencies', 'clients'));
    }

    public function filter(Request $request)
    {
//        dd($request->all());
        if (!check_access("transaction my-list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            }
            $filter['status'] = $request->get('status');
        }
        $transaction_type = $request->input('transaction_type');
        if ($request->has('transaction_type') && !is_null($transaction_type)) {
            if ($transaction_type == "debit") {
                $model->whereRaw('transaction_type in ("debit")');
            } elseif ($transaction_type == "credit") {
                $model->whereRaw('transaction_type in ("credit")');
            }
            $filter['transaction_type'] = $request->get('transaction_type');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by)) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $client_id = $request->input('client_id');
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        $items = $model->orderBy('id', 'desc')->get();
//        dd($filter);
        return view('backend.transactions.my_transactions.filter', compact('items', 'users', 'currencies', 'clients', 'filter'));
    }

}

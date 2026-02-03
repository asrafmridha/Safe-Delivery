<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovedTransactionController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Transaction::orderBy('id', 'desc')->where('status',1)->with('client',"created_user", 'currency','user','supplier')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('transaction view')) {
                    $btn .= '<button class="btn btn-success btn-sm view-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="View transaction"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit')) {
                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('transaction delete')) {
                    $btn .= " <a href='" . route("transaction.delete", $row->id) . "' class='btn btn-danger btn-sm ' title='Delete transaction' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                $btn .= '<button class="btn btn-info btn-sm print-modal ml-1" transaction_id="' . $row->id . '" title="Print transaction"><i class="fa fa-print"></i></button>';

                return $btn;
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('profit', function ($row) {
                return number_format($row->profit, 2);
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
                if (check_permission('transaction status change')) {
                    $btn .= '<button class="btn btn-primary btn-sm mt-1 ml-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function approved(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.transactions.approved');
    }
}

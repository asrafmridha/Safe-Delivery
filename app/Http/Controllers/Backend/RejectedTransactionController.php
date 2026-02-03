<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RejectedTransactionController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Transaction::orderBy('id', 'desc')->where('status',2)->with('client', 'currency')->get();
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
            ->addColumn('status', function ($row) {
                $btn = '';
                if ($row->status == 1) {
                    $btn .= '<p class="badge badge-success">Approved<p/>';
                } elseif ($row->status == 0) {
                    $btn .= '<p class="badge badge-warning">Pending<p/>';
                } elseif ($row->status == 2) {
                    $btn .= '<p class="badge badge-danger">Rejected<p/>';
                }
                $btn .= '<button class="btn btn-primary btn-sm mt-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function rejected(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.transactions.rejected');
    }
}

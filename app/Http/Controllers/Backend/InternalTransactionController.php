<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\InternalTransaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InternalTransactionController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("internal transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }

//        if ($request->ajax()) {
        $data = InternalTransaction::orderBy('id', 'desc')->with('from', 'to', 'created_user');
        if (!check_access("internal transaction all list")) {
            $data->where("from_user_id", auth()->user()->id)->orWhere('to_user_id', auth()->user()->id);
        }
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->to_user_id == auth()->user()->id && $row->status == 0) {
                    $btn .= '<a href="' . route("internal.transaction.approve", $row->id) . '" class="btn btn-success" style="margin-right: 5px"><i class="fa fa-check"></i></a>';
                }
                if ($row->to_user_id == auth()->user()->id && $row->status == 0) {
                    $btn .= '<a href="' . route("internal.transaction.reject", $row->id) . '" class="btn btn-warning"  style="margin-right: 5px"><i class="fa fa-times"></i></a>';
                }
                if (check_permission('internal transaction edit') && $row->status != 1 && $row->created_by == auth()->user()->id) {
                    $btn .= '<a href="' . route("internal.transaction.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('internal transaction delete') && $row->status != 1) {
                    $btn .= " <a href='" . route("internal.transaction.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->editColumn('date', function ($row) {
                return date("d M, Y", strtotime($row->date));
            })
            ->editColumn('attachment', function ($row) {
                $attachment = "--";
                if (file_exists("uploads/attachments/" . $row->attachment)) {
                    $attachment = "<a class='badge badge-primary mt-2'
                                                   href='" . asset('uploads/attachments/' . $row->attachment) . "'
                                                   download
                                                   title='Download attachment'>Download attachment</a>";
                }


                return $attachment;
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ($row->status == 1) {
                    $status .= '<span class="badge badge-success">Approved</span>';
                } elseif ($row->status == 2) {
                    $status .= '<span class="badge badge-danger">Rejected</span>';
                } else {
                    $status .= '<span class="badge badge-warning">Pending</span>';
                }
                return $status;
            })
            ->rawColumns(['action', 'status', 'date', 'attachment'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("internal transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.internal_transaction.index');
    }

    public function create()
    {
        if (!check_access("internal transaction create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::where('status', 1)->where('id', '!=', auth()->user()->id)->get();
        return view('backend.internal_transaction.create', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("internal transaction create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'to_user_id' => 'required',
                'amount' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'date' => $request->input('date'),
                'transaction_no' => "tn" . auth()->user()->id . $request->input('to_user_id') . time(),
                'from_user_id' => auth()->user()->id,
                'to_user_id' => $request->input('to_user_id'),
                'amount' => $request->input('amount'),
                'remarks' => $request->input('remarks'),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ];
            if ($request->file("attachment")) {
                $attachment = $request->file('attachment');
                $newName = 'at_' . time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move('uploads/attachments', $newName);
                $inputs["attachment"] = $newName;
            }
            InternalTransaction::create($inputs);
            Toastr::success("Transaction Created!");
            return redirect()->route('internal.transaction');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = InternalTransaction::find($id);
        if (!check_access("internal transaction edit") && $data->status == 1) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::where('status', 1)->where('id', '!=', auth()->user()->id)->get();
        return view('backend.internal_transaction.edit', compact('data', 'users'));
    }

    public function update(Request $request, $id)
    {
        try {
//            dd($request->all());
            $internalTransaction = InternalTransaction::find($id);
            if (!check_access("internal transaction edit") || $internalTransaction->status == 1) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'to_user_id' => 'required',
                'amount' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'date' => $request->input('date'),
                'to_user_id' => $request->input('to_user_id'),
                'amount' => $request->input('amount'),
                'remarks' => $request->input('remarks'),
                'status' => $request->input('status'),
                'updated_by' => auth()->user()->id,
            ];
            $from_user = User::find($internalTransaction->from_user_id);
            $to_user = User::find($request->input('to_user_id'));
            if ($request->input('status') == 1) {
                $from_user->update(["balance" => $from_user->balance - $request->input('amount')]);
                $to_user->update(["balance" => $to_user->balance + $request->input('amount')]);
            }
            if ($request->file("attachment")) {
                if (file_exists('uploads/attachments/' . $internalTransaction->attachment)) {
                    unlink('uploads/attachments/' . $internalTransaction->attachment);
                }
                $attachment = $request->file('attachment');
                $newName = 'at_' . time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move('uploads/attachments', $newName);
                $inputs["attachment"] = $newName;
            }
            $internalTransaction->update($inputs);
            Toastr::success("Internal Transaction Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $item = InternalTransaction::find($id);
        if (!check_access("internal transaction delete") || $item->status == 1) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        if (file_exists('uploads/attachments/' . $item->attachment)) {
            unlink('uploads/attachments/' . $item->attachment);
        }
        $item->delete();
        Toastr::success("Internal Transaction Deleted!");
        return redirect()->back();
    }

    public function approve($id)
    {
        /*  if (!check_access("internal transaction edit")) {
              Toastr::error("You don't have permission!");
              return redirect()->route('dashboard');
          }*/
        $item = InternalTransaction::find($id);
        if ($item->to_user_id == auth()->user()->id && $item->status == 0) {
            $item->update([
                'status' => 1,
                'updated_by' => auth()->user()->id,
            ]);
            $from_user = User::find($item->from_user_id);
            $to_user = User::find($item->to_user_id);
            $from_user->update(["balance" => $from_user->balance - $item->amount]);
            $to_user->update(["balance" => $to_user->balance + $item->amount]);
        }
        Toastr::success("Internal Transaction Approved!");
        return redirect()->back();
    }

    public function reject($id)
    {
        /* if (!check_access("internal transaction edit")) {
             Toastr::error("You don't have permission!");
             return redirect()->route('dashboard');
         }*/
        $item = InternalTransaction::find($id);
        if ($item->to_user_id == auth()->user()->id && $item->status == 0) {
            $item->update([
                'status' => 2,
                'updated_by' => auth()->user()->id,
            ]);
        }
        Toastr::success("Internal Transaction Rejected!");
        return redirect()->back();
    }


    public function pendingDatatable(Request $request)
    {
        if (!check_access("internal transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }

//        if ($request->ajax()) {
        $data = InternalTransaction::orderBy('id', 'desc')->with('from', 'to', 'created_user')->where('status',0);
        if (!check_access("internal transaction all list")) {
            $data->where("from_user_id", auth()->user()->id)->orWhere('to_user_id', auth()->user()->id);
        }
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->to_user_id == auth()->user()->id && $row->status == 0) {
                    $btn .= '<a href="' . route("internal.transaction.approve", $row->id) . '" class="btn btn-success" style="margin-right: 5px"><i class="fa fa-check"></i></a>';
                }
                if ($row->to_user_id == auth()->user()->id && $row->status == 0) {
                    $btn .= '<a href="' . route("internal.transaction.reject", $row->id) . '" class="btn btn-warning"  style="margin-right: 5px"><i class="fa fa-times"></i></a>';
                }
                if (check_permission('internal transaction edit') && $row->status != 1 && $row->created_by == auth()->user()->id) {
                    $btn .= '<a href="' . route("internal.transaction.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('internal transaction delete') && $row->status != 1) {
                    $btn .= " <a href='" . route("internal.transaction.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->editColumn('date', function ($row) {
                return date("d M, Y", strtotime($row->date));
            })
            ->editColumn('attachment', function ($row) {
                $attachment = "--";
                if (file_exists("uploads/attachments/" . $row->attachment)) {
                    $attachment = "<a class='badge badge-primary mt-2'
                                                   href='" . asset('uploads/attachments/' . $row->attachment) . "'
                                                   download
                                                   title='Download attachment'>Download attachment</a>";
                }


                return $attachment;
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ($row->status == 1) {
                    $status .= '<span class="badge badge-success">Approved</span>';
                } elseif ($row->status == 2) {
                    $status .= '<span class="badge badge-danger">Rejected</span>';
                } else {
                    $status .= '<span class="badge badge-warning">Pending</span>';
                }
                return $status;
            })
            ->rawColumns(['action', 'status', 'date', 'attachment'])
            ->make(true);
//        }
    }

    public function pending(Request $request)
    {
        if (!check_access("internal transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.internal_transaction.pending');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("expense list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Expense::orderBy('id', 'desc')->with('expense_head', 'created_user')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('expense edit') && $row->status != 1) {
                    $btn .= '<a href="' . route("expense.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('expense delete') && $row->status != 1) {
                    $btn .= " <a href='" . route("expense.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
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
        if (!check_access("expense list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.expense.index');
    }

    public function create()
    {
        if (!check_access("expense create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $expenseHeads = ExpenseHead::where('status', 1)->get();
        return view('backend.expense.create', compact('expenseHeads'));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("expense create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'expense_head_id' => 'required',
                'amount' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'date' => $request->input('date'),
                'expense_head_id' => $request->input('expense_head_id'),
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
            Expense::create($inputs);
            Toastr::success("Expense Created!");
            return redirect()->route('expense');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        if (!check_access("expense edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = Expense::find($id);
        $expenseHeads = ExpenseHead::where('status', 1)->get();
        return view('backend.expense.edit', compact('data', 'expenseHeads'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("expense edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $expense = Expense::find($id);
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'expense_head_id' => 'required',
                'amount' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'date' => $request->input('date'),
                'expense_head_id' => $request->input('expense_head_id'),
                'amount' => $request->input('amount'),
                'remarks' => $request->input('remarks'),
                'status' => $request->input('status'),
                'updated_by' => auth()->user()->id,
            ];
            if ($request->file("attachment")) {
                if (file_exists('uploads/attachments/' . $expense->attachment)) {
                    unlink('uploads/attachments/' . $expense->attachment);
                }
                $attachment = $request->file('attachment');
                $newName = 'at_' . time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move('uploads/attachments', $newName);
                $inputs["attachment"] = $newName;
            }

            $from_user = User::find($expense->created_by);
            if ($request->input('status') == 1) {
                if ($from_user->balance<=$request->input('amount') && !check_access('create debit without balance')){
                    Toastr::error("User Don't have enough balance!");
                    return redirect()->back();
                }else{
                    $from_user->update(["balance" => $from_user->balance - $request->input('amount')]);
                }
            }
            $expense->update($inputs);
            Toastr::success("Expense Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("expense delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Expense::find($id);
        if (file_exists('uploads/attachments/' . $item->attachment)) {
            unlink('uploads/attachments/' . $item->attachment);
        }
        $item->delete();
        Toastr::success("Expense Deleted!");
        return redirect()->back();
    }

    public function pendingDatatable(Request $request)
    {
        if (!check_access("expense list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Expense::orderBy('id', 'desc')->with('expense_head', 'created_user')->where('status',0);
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('expense edit') && $row->status != 1) {
                    $btn .= '<a href="' . route("expense.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('expense delete') && $row->status != 1) {
                    $btn .= " <a href='" . route("expense.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
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
        if (!check_access("expense list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.expense.pending');
    }
}

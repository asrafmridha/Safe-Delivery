<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExpenseHeadController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("expense head list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = ExpenseHead::orderBy('title')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('expense head edit')) {
                    $btn .= '<a href="' . route("expense.head.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('expense head delete')) {
                    $btn .= " <a href='" . route("expense.head.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ($row->status == 1) {
                    $status .= '<span class="badge badge-success">Active</span>';
                } else {
                    $status .= '<span class="badge badge-danger">Inactive</span>';
                }
                return $status;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("expense head list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.expense_head.index');
    }

    public function create()
    {
        if (!check_access("expense head create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.expense_head.create');
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("expense head create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:expense_heads,title',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'title' => $request->input('title'),
                'details' => $request->input('details'),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ];
            ExpenseHead::create($inputs);
            Toastr::success("Expense head Created!");
            return redirect()->route('expense.head');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("expense head edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = ExpenseHead::find($id);
        return view('backend.expense_head.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("expense head edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $expenseHead = ExpenseHead::find($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:expense_heads,title,' . $expenseHead->id,
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'title' => $request->input('title'),
                'details' => $request->input('details'),
                'status' => $request->input('status'),
                'updated_by' => auth()->user()->id,
            ];
            $expenseHead->update($inputs);
            Toastr::success("Expense head Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("expense head delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = ExpenseHead::find($id);
        $item->delete();
        Toastr::success("Expense head Deleted!");
        return redirect()->back();
    }
}

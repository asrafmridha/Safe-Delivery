<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("currency list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Currency::orderBy('name')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('currency edit')) {
                    $btn .= '<a href="' . route("currency.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('currency delete')) {
                    $btn .= " <a href='" . route("currency.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("currency list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.currencies.index');
    }

    public function create()
    {
        if (!check_access("currency create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.currencies.create');
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("currency create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:currencies,name',
                'code' => 'required|unique:currencies,code',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ];
            Currency::create($inputs);
            Toastr::success("Currency Created!");
            return redirect()->route('currency');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("currency edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = Currency::find($id);
        return view('backend.currencies.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("currency edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $currency = Currency::find($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:currencies,name,'.$currency->id,
                'code' => 'required|unique:currencies,code,'.$currency->id,
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ];
            $currency->update($inputs);
            Toastr::success("Currency Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        if (!check_access("currency delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Currency::find($id);
        $item->delete();
        Toastr::success("Currency Deleted!");
        return redirect()->back();
    }
}

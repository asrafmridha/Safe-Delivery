<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("payment method list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = PaymentMethod::orderBy('name')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('payment method edit')) {
                    $btn .= '<a href="' . route("payment.method.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('payment method delete')) {
                    $btn .= " <a href='" . route("payment.method.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
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
        if (!check_access("payment method list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.payment_method.index');
    }

    public function create()
    {
        if (!check_access("payment method create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.payment_method.create');
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("payment method create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:payment_methods,name',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'details' => $request->input('details'),
            ];
            PaymentMethod::create($inputs);
            Toastr::success("Payment Method Created!");
            return redirect()->route('payment.method');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("payment method edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = PaymentMethod::find($id);
        return view('backend.payment_method.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("payment method edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $paymentMethod = PaymentMethod::find($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:payment_methods,name,' . $paymentMethod->id,
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'details' => $request->input('details'),
                'status' => $request->input('status'),
            ];
            $paymentMethod->update($inputs);
            Toastr::success("Payment Method Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("payment method delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = PaymentMethod::find($id);
        $item->delete();
        Toastr::success("Payment Method Deleted!");
        return redirect()->back();
    }
}

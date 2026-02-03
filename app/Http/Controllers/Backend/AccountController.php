<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Country;
use App\Models\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{

    public function datatable(Request $request)
    {
        if (!check_access("account list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Account::orderBy('id','desc')->with('country')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('account edit')) {
                    $btn .= '<a href="' . route("account.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('account delete')) {
                    $btn .= " <a href='" . route("account.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
             ->addColumn('status', function ($row) {
                 $btn = '';
                 if ($row->status == 1) {
                     $btn .= '<p class="badge badge-success">Active</p>';
                 } elseif ($row->status == 0) {
                     $btn .= '<p class="badge badge-danger">Inactive</p>';
                 }
                 return $btn;
            })

            ->rawColumns(['action','status'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("account list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.accounts.index');
    }

    public function create()
    {
        if (!check_access("account create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $countries = Country::all();
        return view('backend.accounts.create',compact('countries'));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("account create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'bank_name' => 'required',
                'country_id' => 'required',
                'account_name' => 'required',
                'account_number' => 'required|unique:accounts,account_number',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'country_id' => $request->input('country_id'),
                'bank_name' => $request->input('bank_name'),
                'bank_branch' => $request->input('bank_branch'),
                'account_name' => $request->input('account_name'),
                'account_number' => $request->input('account_number'),
                'route' => $request->input('route'),
                'beneficiary_address' => $request->input('beneficiary_address'),
                'beneficiary_city' => $request->input('beneficiary_city'),
                'beneficiary_swift_code' => $request->input('beneficiary_swift_code'),
            ];
            Account::create($inputs);
            Toastr::success("Account Created!");
            return redirect()->route('account');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("account edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = Account::find($id);
        $countries = Country::all();
        return view('backend.accounts.edit', compact('data',"countries"));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("currency edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $account = Account::find($id);

            $validator = Validator::make($request->all(), [
                'pin' => 'required',
                'bank_name' => 'required',
                'country_id' => 'required',
                'account_name' => 'required',
                'account_number' => 'required|unique:accounts,account_number,'.$account->id,
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            if (auth()->user()->pin != $request->input("pin")){
                Toastr::warning("Wrong pin!");
                return  redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'country_id' => $request->input('country_id'),
                'bank_name' => $request->input('bank_name'),
                'bank_branch' => $request->input('bank_branch'),
                'account_name' => $request->input('account_name'),
                'account_number' => $request->input('account_number'),
                'route' => $request->input('route'),
                'beneficiary_address' => $request->input('beneficiary_address'),
                'beneficiary_city' => $request->input('beneficiary_city'),
                'beneficiary_swift_code' => $request->input('beneficiary_swift_code'),
                'status' => $request->input('status'),
            ];
            $account->update($inputs);
            Toastr::success("Account Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        if (!check_access("account delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Account::find($id);
        $item->delete();
        Toastr::success("Account Deleted!");
        return redirect()->back();
    }
}

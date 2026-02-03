<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("user list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = User::orderBy('id', "desc");

        $data
           /* ->withCount(['transaction as credit' => function ($query) {
                $query->select(DB::raw('SUM(bdt_amount)'))
                    ->where('transaction_type', "credit")
                    ->whereIn('status', [1, 4]);
            }
            ])->withCount(['transaction as debit' => function ($query) {
                $query->select(DB::raw('SUM(bdt_amount)'))
                    ->where('transaction_type', "debit")
                    ->whereIn('status', [1, 4]);
            }
            ])->withCount(['internal_transaction as internal_debit' => function ($query) {
                $query->select(DB::raw('SUM(bdt_amount)'))
                    ->where('transaction_type', "credit")
                    ->whereIn('status', [1, 4]);
            }
            ])->withCount(['internal_transaction as internal_credit' => function ($query) {
                $query->select(DB::raw('SUM(bdt_amount)'))
                    ->where('transaction_type', "debit")
                    ->whereIn('status', [1, 4]);
            }
            ])*/
            ->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('user edit')) {
                    $btn .= '<a href="' . route("user.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('user delete')) {
                    $btn .= " <a href='" . route("user.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->addColumn('balance', function ($row) {
                return number_format($row->balance, 2);
            })
            ->rawColumns(['action', 'balance'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("user list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.users.index');
    }

    public function create()
    {
        if (!check_access("user create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.users.create');
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("user create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:3',
                'user_role' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'password' => Hash::make($request->input('password')),
                'designation' => $request->input('designation'),
                'user_role' => $request->input('user_role'),
            ];
            User::create($inputs);
            Toastr::success("User Created!");
            return redirect()->route('user');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("user edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        return view('backend.users.edit', compact('user'));
    }

    public function profile()
    {
        /*  if (!check_access("user edit")) {
              Toastr::error("You don't have permission!");
              return redirect()->route('dashboard');
          }*/
        $user = User::find(auth()->user()->id);
        return view('backend.users.profile', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("user edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $user = User::find($id);
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:users,email,' . $user->id,
                'user_role' => 'required',
                'status' => 'required',
                'pin' => 'required',
            ];
            if ($request->input('password')) {
                $rules['password'] = "min:3";
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'designation' => $request->input('designation'),
                'user_role' => $request->input('user_role'),
                'status' => $request->input('status'),
                'pin' => $request->input('pin'),
            ];
            if ($request->input('password')) {
                $inputs['password'] = Hash::make($request->input('password'));
            }
            $user->update($inputs);
            Toastr::success("User Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function changePassword(Request $request)
    {
        try {
            /*if (!check_access("user edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }*/
//            dd($request->all());
            $user = User::find(auth()->user()->id);
            $rules = [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:3',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $creds = [
                'email' => auth()->user()->email,
                'password' => $request->input('old_password'),
            ];
            if (auth()->attempt($creds)) {
                $inputs = [
                    'password' => Hash::make($request->input('password'))
                ];
                $user->update($inputs);
                Toastr::success("Password Changed!");
                return redirect()->back();
            }
            Toastr::warning("Wrong old password!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function changePin(Request $request)
    {
        try {
            /*if (!check_access("user edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }*/
//            dd($request->all());
            $user = User::find(auth()->user()->id);
            $rules = [
                'old_pin' => 'required',
                'pin' => 'required|confirmed|min:1',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            if (auth()->user()->pin == $request->input('old_pin')) {
                $inputs = [
                    'pin' => $request->input('pin')
                ];
                $user->update($inputs);
                Toastr::success("Pin Changed!");
                return redirect()->back();
            }
            Toastr::warning("Wrong old pin!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("user delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = User::find($id);
        $item->delete();
        Toastr::success("User Deleted!");
        return redirect()->back();
    }
}

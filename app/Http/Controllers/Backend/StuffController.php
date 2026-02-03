<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StuffController extends Controller
{
    public function index()
    {
        if (!check_access("stuff list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::orderBy('id', 'desc')->where('user_role', 'stuff')->get();
//        dd($users);
        return view('backend.stuffs.index', compact('users'));
    }

    public function edit($id)
    {
        if (!check_access("stuff edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        $roles = Role::all();
        return view('backend.stuffs.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("stuff edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            /*$validator = Validator::make($request->all(), [
                'name' => 'required',
                'user_role' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }*/
            $user = User::find($id);
            $roles = [];
            if ($request->input('role')) {
                $roles = $request->input('role');
            }
          /*  $inputs = [
                'name' => $request->input('name'),
                'user_role' => $request->input('user_role'),
            ];
            $user->update($inputs);*/
            foreach ($user->roles as $item) {
                if (!key_exists($item->id, $roles)) {
                    $user->removeRole($item->id);
                }
            }
            foreach ($roles as $key => $role) {
                $role = Role::findById($key);
                $user->assignRole($role);
            }
            Toastr::success("Role assigned successfully!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }
}

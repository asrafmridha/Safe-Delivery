<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        if (!check_access("role list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $roles = Role::all();
        return view('backend.users.role', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("role create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            Role::create(['name' => $request->input('name')]);
            Toastr::success("Role created!");
            return redirect()->route('role');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("role edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('backend.users.role_edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
//        dd($request->all());
        try {
            if (!check_access("role edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $role = Role::findById($id);
            $permissions = [];
            if ($request->input('permission')) {
                $permissions = $request->input('permission');
            }
            $role->update(['name' => $request->input('name')]);
            $roles = $role->permissions;
            foreach ($roles as $item) {
                if (!key_exists($item->id, $permissions)) {
                    $role->revokePermissionTo($item->id);
                }
            }
            foreach ($permissions as $key => $permission) {
                $permission = Permission::findById($key);
                $role->givePermissionTo($permission);
            }
            Toastr::success("Role Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("role delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Role::findById($id);
        $item->delete();
        Toastr::success("Role Deleted!");
        return redirect()->back();
    }
}

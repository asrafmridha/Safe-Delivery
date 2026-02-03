<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class AdminController extends Controller {

    public function index() {
        $data               = [];
        $data['main_menu']  = 'setting';
        $data['child_menu'] = 'admin';
        $data['page_title'] = 'Admins';
        return view('admin.setting.admin.index', $data);
    }

    public function getAdmins(Request $request){
        $model  = Admin::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('type', function ($data) {
                    switch($data->type){
                        case 1 : $type  = "Admin"; break;
                        case 2 : $type  = "Operation"; break;
                        case 3 : $type  = "Accounts"; break;
                        case 4 : $type  = "CS"; break;
                        case 5 : $type  = "Business Development"; break;
                        case 6 : $type  = "General User"; break;
                        default : $type  = "General User"; break;
                    }
                    return $type;
                })
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->photo)){
                    $image = ' <img src="'. asset('uploads/admin/'.$data->photo) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Admin Photo">';
                    }
                    return $image;
                })
                ->addColumn('status', function($data){
                    if($data->id != auth()->guard('admin')->user()->id ){
                        if($data->status  == 1){
                            $class =  "success"; $status = 0; $status_name = "Active";
                        }
                        else{
                            $class =  "danger";  $status = 1; $status_name = "Inactive";
                        }
                        return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" admin_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                    }
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" admin_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.admin.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    if($data->id != auth()->guard('admin')->user()->id ){
                        $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" admin_id="'.$data->id.'">
                        <i class="fa fa-trash"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['category_name', 'status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'setting';
        $data['child_menu'] = 'admin';
        $data['page_title'] = 'Create Admin';
        return view('admin.setting.admin.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|min:2',
            'contact_number'    => 'required|unique:admins',
            'email'    => 'required|email|unique:admins',
            'password' => 'required|min:5|max:100',
            'type'     => 'required',
            'photo'    => 'sometimes|image|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $photo_name = null;

        if ($request->hasFile('photo')) {
            $photo      = $request->file('photo');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/admin/' . $photo_name;
            Image::make($photo)->save($photo_path);
        }

        $data = [
            'name'     => $request->input('name'),
            'contact_number'    => $request->input('contact_number'),
            'email'    => $request->input('email'),
            'type'     => $request->input('type'),
            'photo'    => $photo_name,
        ];
        if(!empty($request->input('password'))){
            $data['password']       = bcrypt($request->input('password'));
            $data['store_password'] = $request->input('password');
        }

        $check = Admin::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Admin Create Successfully', 'success');
            return redirect()->route('admin.admin.index');
        } else {
            $this->setMessage('Admin Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Admin $admin) {
        return view('admin.setting.admin.show', compact('admin'));
    }

    public function edit(Request $request, Admin $admin) {
        $data               = [];
        $data['main_menu']  = 'setting';
        $data['child_menu'] = 'admin';
        $data['page_title'] = 'Edit Admin';
        $data['admin']      = $admin;
        return view('admin.setting.admin.edit', $data);
    }

    public function update(Request $request, Admin $admin) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|min:2',
            'contact_number'    => 'required|unique:admins,contact_number,' . $admin->id,
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'password' => $request->password != null ? 'sometimes|min:5|max:100' : '',
            'type'     => 'required',
            'photo'    => 'sometimes|image|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $photo_name = $admin->photo;

        if ($request->hasFile('photo')) {
            $photo      = $request->file('photo');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/admin/' . $photo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($admin->photo)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/admin/' . $admin->photo;
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }

        $data = [
            'name'  => $request->input('name'),
            'contact_number' => $request->input('contact_number'),
            'email' => $request->input('email'),
            'type'  => $request->input('type'),
            'photo' => $photo_name,
        ];

        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->input('password'));
            $data['store_password'] = $request->input('password');
        }

        $check = Admin::where('id', $admin->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Admin Update Successfully', 'success');
            return redirect()->route('admin.admin.index');
        } else {
            $this->setMessage('Admin Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'admin_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Admin::where('id', $request->admin_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Admin Status Update Successfully',
                        'status'  => $request->status,
                    ];
                }
                else{
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }
        return response()->json($response);
    }

    public function destroy(Request $request, Admin $admin) {

        if (!empty($admin->photo)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/admin/' . $admin->photo;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $admin->delete() ? true : false;

        if ($check) {
            $this->setMessage('Admin Delete Successfully', 'success');
        } else {
            $this->setMessage('Admin Delete Failed', 'danger');
        }
        return redirect()->route('admin.admin.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'admin_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $admin = Admin::where('id', $request->admin_id)->first();
                $check = Admin::where('id', $request->admin_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($admin->photo)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/admin/' . $admin->photo;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Admin Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

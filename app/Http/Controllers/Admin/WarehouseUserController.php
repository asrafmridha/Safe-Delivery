<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\WarehouseUser;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Warehouse;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class WarehouseUserController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'warehouseUser';
        $data['collapse'] = 'sidebar-collapse';
        $data['page_title'] = 'Warehouse Users';
        return view('admin.team.warehouseUser.index', $data);
    }

    public function getWarehouseUsers(Request $request)
    {
        $model = WarehouseUser::with(['warehouse'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";
                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/warehouseUser/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Warehouse User Image">';
                }
                return $image;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success";
                    $status = 0;
                    $status_name = "Active";
                } else {
                    $class = "danger";
                    $status = 1;
                    $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" warehouse_user_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" warehouse_user_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('admin.warehouseUser.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" warehouse_user_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function printWarehouseUsers(Request $request)
    {
        $warehouseUsers = WarehouseUser::with(['warehouse'])->orderBy('id','desc')->get();
        return view('admin.team.warehouseUser.print', compact('warehouseUsers'));
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";
                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/warehouseUser/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Warehouse User Image">';
                }
                return $image;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success";
                    $status = 0;
                    $status_name = "Active";
                } else {
                    $class = "danger";
                    $status = 1;
                    $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" warehouse_user_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" warehouse_user_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('admin.warehouseUser.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" warehouse_user_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'warehouseUser';
        $data['page_title'] = 'Create Warehouse User';
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        return view('admin.team.warehouseUser.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:warehouse_users',
            'name' => 'required',
            'address' => 'required',
            'warehouse_id' => 'required',
            'contact_number' => 'required',
            'password' => 'sometimes',
            'image' => 'sometimes|image|max:2000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/warehouseUser/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $password = $request->input('password') ?? 12345;
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'warehouse_id' => $request->input('warehouse_id'),
            'contact_number' => $request->input('contact_number'),
            'password' => bcrypt($password),
            'store_password' => $password,
            'image' => $image_name,
            'date' => date("Y-m-d"),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = warehouseUser::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Warehouse User Create Successfully', 'success');
            return redirect()->route('admin.warehouseUser.index');
        } else {
            $this->setMessage('Warehouse User Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, warehouseUser $warehouseUser)
    {
        $warehouseUser->load(['warehouse']);
        return view('admin.team.warehouseUser.show', compact('warehouseUser'));
    }

    public function edit(Request $request, warehouseUser $warehouseUser)
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'warehouseUser';
        $data['page_title'] = 'Edit Warehouse User';
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        $data['warehouseUser'] = $warehouseUser;
        return view('admin.team.warehouseUser.edit', $data);
    }

    public function update(Request $request, warehouseUser $warehouseUser)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:warehouse_users,email,' . $warehouseUser->id,
            'name' => 'required',
            'address' => 'required',
            'warehouse_id' => 'required',
            'contact_number' => 'required',
            'password' => 'sometimes',
            'image' => 'sometimes|image|max:2000',
            'status' => 'sometimes',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $image_name = $warehouseUser->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/warehouseUser/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($warehouseUser->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/warehouseUser/' . $warehouseUser->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }

        $password = $request->input('password') ?? 12345;
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'warehouse_id' => $request->input('warehouse_id'),
            'contact_number' => $request->input('contact_number'),
            'password' => bcrypt($password),
            'store_password' => $password,
            'image' => $image_name,
            'status' => $request->input('status'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = WarehouseUser::where('id', $warehouseUser->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Warehouse User Update Successfully', 'success');
            return redirect()->route('admin.warehouseUser.index');
        } else {
            $this->setMessage('Warehouse User Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'warehouse_user_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = WarehouseUser::where('id', $request->warehouse_user_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Branch Status Update Successfully',
                        'status' => $request->status,
                    ];
                } else {
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request, Warehouse $warehouse)
    {
        $check = $warehouse->delete() ? true : false;

        if ($check) {
            $this->setMessage('Warehouse User Delete Successfully', 'success');
        } else {
            $this->setMessage('Warehouse User Delete Failed', 'danger');
        }

        return redirect()->route('admin.warehouseUser.index');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'warehouse_user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $warehouseUser = WarehouseUser::where('id', $request->warehouse_user_id)->first();
                $check = WarehouseUser::where('id', $request->warehouse_user_id)->delete() ? true : false;

                if ($check) {

                    if (!empty($warehouseUser->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/warehouseUser/' . $warehouseUser->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }
                    $response = ['success' => 'Warehouse User Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function warehouseUsersResult(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'warehouse_user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $warehouse = warehouseUser::with('branch')->where('id', $request->warehouse_user_id)->first();
                if ($warehouse) {
                    $response = [
                        'success' => 1,
                        'warehouse' => $warehouse
                    ];
                } else {
                    $response = ['error' => 1];
                }
            }
        }
        return response()->json($response);
    }

}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\District;
use App\Models\Upazila;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BranchUserController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branchUser';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Branch Users';
        return view('admin.team.branchUser.index', $data);
    }

    public function getBranchUsers(Request $request) {
        $model = BranchUser::with(['branch', 'branch.district', 'branch.area'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";
                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/branchUser/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Branch User Image">';
                }
                return $image;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" branch_user_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" branch_user_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('admin.branchUser.branchUserLogin', $data->id) . '" class="btn btn-success btn-sm" target="_blank"> <i class="fas fa-sign-in-alt"></i> </a>&nbsp;&nbsp;';

                $button .= '<a href="' . route('admin.branchUser.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';

                // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" branch_user_id="' . $data->id . '">
                //     <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }
    public function printBranchUsers(Request $request) {
        $branchUsers = BranchUser::with(['branch', 'branch.district', 'branch.area'])->orderBy('id','desc')->get();
        return view('admin.team.branchUser.print', compact('branchUsers'));
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branchUser';
        $data['page_title'] = 'Create Branch User';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.team.branchUser.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|unique:branch_users',
            'name'           => 'required',
            'address'        => 'required',
            'branch_id'      => 'required',
            'contact_number' => 'required',
            'password'       => 'sometimes',
            'image'          => 'sometimes|image',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/branchUser/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $password = $request->input('password') ?? 12345;
        $data     = [
            'email'            => $request->input('email'),
            'name'             => $request->input('name'),
            'address'          => $request->input('address'),
            'branch_id'        => $request->input('branch_id'),
            'contact_number'   => $request->input('contact_number'),
            'password'         => bcrypt($password),
            'store_password'   => $password,
            'image'            => $image_name,
            'date'             => date("Y-m-d"),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = BranchUser::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Branch User Create Successfully', 'success');
            return redirect()->route('admin.branchUser.index');
        } else {
            $this->setMessage('Branch User Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, BranchUser $branchUser) {
        $branchUser->load(['branch']);
        return view('admin.team.branchUser.show', compact('branchUser'));
    }

    public function edit(Request $request, BranchUser $branchUser) {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branchUser';
        $data['page_title'] = 'Edit branch User';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['branchUser'] = $branchUser;
        return view('admin.team.branchUser.edit', $data);
    }

    public function update(Request $request, BranchUser $branchUser) {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|unique:branch_users,email,' . $branchUser->id,
            'name'           => 'required',
            'address'        => 'required',
            'branch_id'    => 'required',
            'contact_number' => 'required',
            'password'       => 'sometimes',
            'image'          => 'sometimes|image',
            'status'         => 'sometimes',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/branchUser/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($branchUser->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/branchUser/' . $branch->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            $image_name = $branchUser->image;
        }

        $password = $request->input('password') ?? 12345;
        $data     = [
            'email'            => $request->input('email'),
            'name'             => $request->input('name'),
            'address'          => $request->input('address'),
            'branch_id'        => $request->input('branch_id'),
            'contact_number'   => $request->input('contact_number'),
            'password'         => bcrypt($password),
            'store_password'   => $password,
            'image'            => $image_name,
            'status'           => $request->input('status'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = BranchUser::where('id', $branchUser->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Branch User Update Successfully', 'success');
            return redirect()->route('admin.branchUser.index');
        } else {
            $this->setMessage('Branch User Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_user_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = BranchUser::where('id', $request->branch_user_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Branch Status Update Successfully',
                        'status'  => $request->status,
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

    public function destroy(Request $request, Branch $branch) {
        $check = $branch->delete() ? true : false;

        if ($check) {
            $this->setMessage('Branch User Delete Successfully', 'success');
        } else {
            $this->setMessage('Branch User Delete Failed', 'danger');
        }

        return redirect()->route('admin.branchUser.index');
    }

    public function delete(Request $request) {
        // dd($request->all());
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $branchUser = BranchUser::where('id', $request->branch_user_id)->first();
                $check  = BranchUser::where('id', $request->branch_user_id)->delete() ? true : false;

                if ($check) {

                    if (!empty($branchUser->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/branchUser/' . $branchUser->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }
                    $response = ['success' => 'Branch User Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function branchUserLogin(BranchUser $branchUser) {
        if($branchUser){
            auth()->guard('branch')->login($branchUser);

            $notification = new \App\Http\Controllers\AuthController;
            $notification->setApplicationInformationIntoSession();

            $this->setMessage('Branch Login Successfully', 'success');
            return redirect()->route('branch.home');
        }
    }

    public function branchUsersResult(Request $request) {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $branch = BranchUser::with('branch')->where('id', $request->branch_user_id)->first();
                if ($branch) {
                    $response = [
                        'success' => 1,
                        'branch' => $branch
                    ];
                } else{
                    $response = ['error' => 1];
                }
            }
        }
        return response()->json($response);
    }

}


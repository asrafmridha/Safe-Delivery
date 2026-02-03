<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Upazila;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BranchController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branch';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Branches';
        return view('admin.team.branch.index', $data);
    }

    public function getBranches(Request $request) {
        $model = Branch::with(['parent_branch', 'district',  'area'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";

                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/branch/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Branch Image">';
                }

                return $image;
            })
            ->editColumn('type', function ($data) {

                if ($data->type == 1) {
                    $type = "Parent";
                } else {
                    $type = "Sub-Branch";
                }

                return $type;
            })
            ->editColumn('parent_id', function ($data) {

                $branch_name = ($data->parent_branch) ? $data->parent_branch->name : "Default";

                return $branch_name;
            })
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" branch_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" branch_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                if(auth()->guard('admin')->user()->type == 1) {
                    $button .= '<a href="' . route('admin.branch.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                    // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" branch_id="' . $data->id . '"><i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }
    public function printBranches(Request $request) {
        $branches = Branch::with(['parent_branch', 'district',  'area'])->orderBy('id','desc')->get();
        return view('admin.team.branch.print', compact('branches'));
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branch';
        $data['page_title'] = 'Create Branch';
        $data['districts']  = District::where('status', 1)->get();
        $data['branches']   = Branch::where('status', 1)
                                        ->where('type', 1)->get();
        return view('admin.team.branch.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|unique:branches',
            'name'           => 'required',
            'address'        => 'required',
            'type'           => 'required',
            'parent_id'      => 'sometimes',
            'district_id'    => 'required',
            // 'upazila_id'     => 'required',
            'area_id'        => 'required',
            'contact_number' => 'required',
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
            $image_path = 'public/uploads/branch/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data     = [
            'email'            => $request->input('email'),
            'name'             => $request->input('name'),
            'address'          => $request->input('address'),
            'type'             => $request->input('type'),
            'parent_id'        => $request->input('parent_id'),
            'district_id'      => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id'       => $request->input('upazila_id') ?? 0,
            'area_id'          => $request->input('area_id'),
            'contact_number'   => $request->input('contact_number'),
            'image'            => $image_name,
            'date'             => date("Y-m-d"),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Branch::create($data) ? true : false;

        if ($check) {
            // $this->adminDashboardCounterEvent();

            $this->setMessage('Branch Create Successfully', 'success');
            return redirect()->route('admin.branch.index');
        } else {
            $this->setMessage('Branch Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, Branch $branch) {
        $branch->load(['district', 'upazila', 'area', 'merchants', 'riders', 'branch_users']);
        return view('admin.team.branch.show', compact('branch'));
    }

    public function edit(Request $request, Branch $branch) {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'branch';
        $data['page_title'] = 'Edit branch';
        $data['districts']  = District::where('status', 1)->get();
        // $data['upazilas']   = Upazila::where('district_id', $branch->district_id)->get();
        $data['areas']      = Area::where('district_id', $branch->district_id)->get();
        $data['branch']     = $branch;
        return view('admin.team.branch.edit', $data);
    }

    public function update(Request $request, Branch $branch) {

        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|unique:branches,email,' . $branch->id,
            'name'           => 'required',
            'address'        => 'required',
            'district_id'    => 'required',
            // 'upazila_id'     => 'required',
            'area_id'        => 'required',
            'contact_number' => 'required',
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
            $image_path = 'public/uploads/branch/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($branch->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/branch/' . $branch->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            $image_name = $branch->image;
        }

        $data     = [
            'email'            => $request->input('email'),
            'name'             => $request->input('name'),
            'address'          => $request->input('address'),
            'district_id'      => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id'       => 0,
            'area_id'          => $request->input('area_id'),
            'contact_number'   => $request->input('contact_number'),
            'image'            => $image_name,
            'status'           => $request->input('status'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Branch::where('id', $branch->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Branch Update Successfully', 'success');
            return redirect()->route('admin.branch.index');
        } else {
            $this->setMessage('Branch Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Branch::where('id', $request->branch_id)->update(['status' => $request->status]) ? true : false;

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
            $this->setMessage('Branch Delete Successfully', 'success');
        } else {
            $this->setMessage('Branch Delete Failed', 'danger');
        }

        return redirect()->route('admin.branch.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $branch = Branch::where('id', $request->branch_id)->first();
                $check  = Branch::where('id', $request->branch_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($branch->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/branch/' . $branch->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Branch Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function branchResult(Request $request) {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $branch = Branch::with('district', 'upazila', 'area')->where('id', $request->branch_id)->first();
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

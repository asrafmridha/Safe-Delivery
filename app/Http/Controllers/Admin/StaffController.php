<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_list';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Staff';
        return view('admin.account.salary.staff.index', $data);
    }

    public function getStaffs(Request $request) {
        $model = Staff::with(['branch'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";

                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/staff/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Rider Image">';
                }
                return $image;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" staff_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" staff_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('admin.staff.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" staff_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_list';
        $data['page_title'] = 'Create Staff';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.salary.staff.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required',
            'address'         => 'required',
            'branch_id'       => 'required',
            'phone'           => 'required|unique:staff',
            'image'           => 'sometimes|image',
        ], [
            'phone.unique' => 'This Phone Number Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/staff/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data     = [
            'name'             => $request->input('name'),
            'phone'             => $request->input('phone'),
            'email'             => $request->input('email'),
            'designation'       => $request->input('designation'),
            'address'          => $request->input('address'),
            'branch_id'        => $request->input('branch_id'),
            'salary'            => $request->input('salary'),
            'image'            => $image_name,
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Staff::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Staff Create Successfully', 'success');
            return redirect()->route('admin.staff.index');
        } else {
            $this->setMessage('Staff Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        $staff->load(['branch']);
        return view('admin.account.salary.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Staff $staff)
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_list';
        $data['page_title'] = 'Edit Staff';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['staff']     = $staff;
        return view('admin.account.salary.staff.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff) {

        $validator = Validator::make($request->all(), [
            'name'           => 'required',
            'address'        => 'required',
            'branch_id'      => 'required',
            'phone'          => 'required|unique:staff,phone,' . $staff->id,
            'image'          => 'sometimes|image',
            'status'         => 'sometimes',
        ], [
            'phone.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/staff/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($staff->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/rider/' . $rider->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            $image_name = $staff->image;
        }

        $data     = [
            'name'             => $request->input('name'),
            'phone'             => $request->input('phone'),
            'email'             => $request->input('email'),
            'designation'             => $request->input('designation'),
            'address'          => $request->input('address'),
            'branch_id'        => $request->input('branch_id'),
            'salary'             => $request->input('salary'),
            'image'            => $image_name,
            'status'           => $request->input('status'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Staff::where('id', $staff->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Staff Update Successfully', 'success');
            return redirect()->route('admin.staff.index');
        } else {
            $this->setMessage('Staff Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }



    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'staff_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Staff::where('id', $request->staff_id)->update(['status' => $request->status]) ? true : false;
                if ($check) {
                    $response = [
                        'success' => 'Staff Status Update Successfully',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Staff $staff) {
        $check = $staff->delete() ? true : false;

        if ($check) {
            $this->setMessage('Staff Delete Successfully', 'success');
        } else {
            $this->setMessage('Staff Delete Failed', 'danger');
        }
        return redirect()->route('admin.staff.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'staff_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $staff = Staff::where('id', $request->staff_id)->first();
                $check  = Staff::where('id', $request->staff_id)->delete() ? true : false;

                if ($check) {

                    if (!empty($staff->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/staff/' . $staff->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }
                    $response = ['success' => 'Staff Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }
}

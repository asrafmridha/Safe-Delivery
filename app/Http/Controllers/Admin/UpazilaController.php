<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Upazila;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpazilaController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'upazila';
        $data['page_title'] = 'Thana/Upazilas';
        return view('admin.applicationSetting.upazila.index', $data);
    }

    public function getUpazilas(Request $request) {
        $model = Upazila::with('district')->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" upazila_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('admin.upazila.edit', $data->id) . '" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" upazila_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'upazila';
        $data['page_title'] = 'Create Upazila';
        $data['districts']  = District::where('status', 1)->get();
        return view('admin.applicationSetting.upazila.create', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:upazilas,name,NULL,id,district_id,' . $request->input('district_id'),
            'district_id' => 'required',
        ], [
            'name.unique' => 'This Upazila and District Already Inserted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'             => $request->input('name'),
            'district_id'      => $request->input('district_id'),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Upazila::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Thana/Upazila Create Successfully', 'success');
            return redirect()->route('admin.upazila.index');
        } else {
            $this->setMessage('Thana/Upazila Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Upazila $upazila) {
        return view('admin.applicationSetting.upazila.show', compact('upazila'));
    }

    public function edit(Request $request, Upazila $upazila) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'upazila';
        $data['page_title'] = 'Edit Upazila';
        $data['districts']  = District::where('status', 1)->get();
        $data['upazila']    = $upazila;
        return view('admin.applicationSetting.upazila.edit', $data);
    }

    public function update(Request $request, Upazila $upazila) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:upazilas,name,' . $upazila->id . ',id,district_id,' . $request->input('district_id'),
            'district_id' => 'required',
        ], [
            'name.unique' => 'This Thana/Upazila and District Already Inserted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'             => $request->input('name'),
            'district_id'      => $request->input('district_id'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Upazila::where('id', $upazila->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Thana/Upazila Update Successfully', 'success');
            return redirect()->route('admin.upazila.index');
        } else {
            $this->setMessage('Thana/Upazila Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'upazila_id' => 'required',
                'status'     => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Upazila::where('id', $request->upazila_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Thana/Upazila Status Update Successfully',
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

    public function destroy(Request $request, Upazila $upazila) {
        $check = $upazila->delete() ? true : false;

        if ($check) {
            $this->setMessage('Thana/Upazila Delete Successfully', 'success');
        } else {
            $this->setMessage('Thana/Upazila Delete Failed', 'danger');
        }

        return redirect()->route('admin.upazila.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'upazila_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            } else {
                $check = Upazila::where('id', $request->upazila_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Thana/Upazila Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function districtOption(Request $request) {

        if ($request->ajax()) {
            $option   = '<option value="0">Select Thana/Upazila</option>';
            $upazilas = Upazila::where('district_id', $request->district_id)->get();

            foreach ($upazilas as $upazila) {
                $option .= '<option value="' . $upazila->id . '">' . $upazila->name . '</option>';
            }

            return response()->json(['option' => $option]);
        }

        return redirect()->back();
    }

}

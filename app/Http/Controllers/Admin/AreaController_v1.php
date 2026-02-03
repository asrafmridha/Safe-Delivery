<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Models\Area;
use App\Models\District;
use App\Models\Upazila;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;


class AreaController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Areas';
        return view('admin.applicationSetting.area.index', $data);
    }

    public function getAreas(Request $request) {
        $model = Area::with('upazila', 'district')->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" area_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('admin.area.edit', $data->id) . '" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" area_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Create Area';
        $data['districts']  = District::where('status', 1)->get();
        return view('admin.applicationSetting.area.create', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            // 'name'        => 'required|unique:areas,name,NULL,id,upazila_id,' . $request->input('upazila_id') . ',district_id,' . $request->input('district_id'),
            'name'        => 'required|unique:areas,name,NULL,id,district_id,' . $request->input('district_id'),
            // 'upazila_id'  => 'required',
            'district_id' => 'required',
            'post_code'   => 'sometimes',
        ], [
            'name.unique' => 'This Area, Upazila and District Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'             => $request->input('name'),
            'post_code'        => $request->input('post_code'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id'       => 0,
            'district_id'      => $request->input('district_id'),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Area::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Area Create Successfully', 'success');
            return redirect()->route('admin.area.index');
        } else {
            $this->setMessage('Area Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Area $area) {
        return view('admin.applicationSetting.area.show', compact('area'));
    }

    public function edit(Request $request, Area $area) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Edit Area';
        $data['districts']  = District::where('status', 1)->get();
        // $data['upazilas']   = Upazila::where('district_id', $area->district_id)->get();
        $data['area']       = $area;
        return view('admin.applicationSetting.area.edit', $data);
    }

    public function update(Request $request, Area $area) {
        $validator = Validator::make($request->all(), [
            // 'name'        => 'required|unique:areas,name,' . $area->id . ',id,upazila_id,' . $request->input('upazila_id') . ',district_id,' . $request->input('district_id'),
            'name'        => 'required|unique:areas,name,' . $area->id . ',id,district_id,' . $request->input('district_id'),
            // 'upazila_id'  => 'required',
            'district_id' => 'required',
            'post_code'   => 'sometimes',
        ], [
            'name.unique' => 'This Area, Upazila and District Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'             => $request->input('name'),
            'post_code'        => $request->input('post_code'),
            'district_id'      => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id'       => 0,
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Area::where('id', $area->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Area Update Successfully', 'success');
            return redirect()->route('admin.area.index');
        } else {
            $this->setMessage('Area Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'area_id' => 'required',
                'status'  => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Area::where('id', $request->area_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Area Status Update Successfully',
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

    public function destroy(Request $request, Area $area) {
        $check = $area->delete() ? true : false;

        if ($check) {
            $this->setMessage('Area Delete Successfully', 'success');
        } else {
            $this->setMessage('Area Delete Failed', 'danger');
        }

        return redirect()->route('admin.area.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'area_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            } else {
                $check = Area::where('id', $request->area_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Area Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function areaOption(Request $request) {
        if ($request->ajax()) {
            $option = '<option value="0">Select Area</option>';
            $areas = Area::where('upazila_id', $request->upazila_id)->get();
            foreach ($areas as $area) {
                $option .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }

    public function districtWiseAreaOption(Request $request) {
        if ($request->ajax()) {
            $option = '<option value="0">Select Area</option>';
            $areas = Area::where('district_id', $request->district_id)->get();
            foreach ($areas as $area) {
                $option .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }


    public function excelImport() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Create Area';
        return view('admin.applicationSetting.area.excel_import', $data);
    }


    public function excelImportStore(Request $request) {

        Area::truncate();

        $file = $request->file('file')->store('import');

        $import = new AreaImport;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        $this->setMessage('Area Create Successfully', 'success');
        return redirect()->route('admin.area.index');

    }

}

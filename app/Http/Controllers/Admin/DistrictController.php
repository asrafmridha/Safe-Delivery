<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Models\ServiceArea;

class DistrictController extends Controller
{
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'district';
        $data['page_title'] = 'Districts';
        return view('admin.applicationSetting.district.index', $data);
    }

    public function getDistricts(Request $request){
        $model  = District::with('service_area')->select();

        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('home_delivery', function($data){
                    if($data->home_delivery  == 1){
                        $home_delivery =  "Yes";
                    }
                    else{
                        $home_delivery =  "No";
                    }
                    return $home_delivery;
                })
                ->editColumn('lock_down_service', function($data){
                    if($data->lock_down_service  == 1){
                        $lock_down_service =  "Yes";
                    }
                    else{
                        $lock_down_service =  "No";
                    }
                    return $lock_down_service;
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" district_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('admin.district.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" district_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['home_delivery', 'lock_down_service', 'status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'district';
        $data['page_title'] = 'Create district';
        $data['serviceAreas'] = ServiceArea::where([['status','=', 1], ['weight_type','=', 1]])->get();
        return view('admin.applicationSetting.district.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:districts',
            'service_area_id'    => 'required',
            'home_delivery'    => 'required',
            'lock_down_service'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'service_area_id'   => $request->input('service_area_id'),
            'home_delivery'     => $request->input('home_delivery'),
            'lock_down_service' => $request->input('lock_down_service'),
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = District::create($data) ? true : false;

        if ($check) {
            $this->setMessage('District Create Successfully', 'success');
            return redirect()->route('admin.district.index');
        } else {
            $this->setMessage('District Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, District $district) {
        return view('admin.applicationSetting.district.show', compact('district'));
    }

    public function edit(Request $request, District $district) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'district';
        $data['page_title'] = 'Edit district';
        $data['serviceAreas'] = ServiceArea::where([['status','=', 1], ['weight_type','=', 1]])->get();
        $data['district']= $district;
        return view('admin.applicationSetting.district.edit', $data);
    }

    public function update(Request $request, District $district) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:districts,name,' . $district->id,
            'service_area_id'    => 'required',
            'home_delivery'    => 'required',
            'lock_down_service'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'service_area_id'   => $request->input('service_area_id'),
            'home_delivery'     => $request->input('home_delivery'),
            'lock_down_service' => $request->input('lock_down_service'),
            'updated_admin_id'  => auth()->guard('admin')->user()->id,
        ];

        $check = District::where('id', $district->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('District Update Successfully', 'success');
            return redirect()->route('admin.district.index');
        } else {
            $this->setMessage('District Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'district_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            }
            else{
                $check = District::where('id', $request->district_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'District Status Update Successfully',
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

    public function destroy(Request $request, District $district) {
        $check = $district->delete() ? true : false;

        if ($check) {
            $this->setMessage('District Delete Successfully', 'success');
        } else {
            $this->setMessage('District Delete Failed', 'danger');
        }
        return redirect()->route('admin.district.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'district_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found 2'];
            }
            else{
                $check = District::where('id', $request->district_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'District Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function districtByDivision(Request $request) {

        if ($request->ajax()) {
            $option   = '<option value="0">Select District</option>';
            $districts = District::where('division_id', $request->division_id)->get();

            foreach ($districts as $district) {
                $option .= '<option value="' . $district->id . '">' . $district->name . '</option>';
            }

            return response()->json(['option' => $option]);
        }

        return redirect()->back();
    }


}

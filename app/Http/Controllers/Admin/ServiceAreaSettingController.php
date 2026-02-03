<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use App\Models\WeightPackage;
use App\Models\ServiceAreaSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServiceAreaSettingController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceAreaSetting';
        $data['page_title'] = 'Service Areas';
        return view('admin.applicationSetting.serviceAreaSetting.index', $data);
    }

    public function getServiceAreaSettings(Request $request){
        $model  = ServiceAreaSetting::with(['service_area', 'weight_packages'])->select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" service_area_setting_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('weightPackage', function($data){
                    $html = "";
                    if($data->weight_packages->count() > 0){
                        $sl = 0;
                        foreach($data->weight_packages as $weight_package){
                            $html .= " <b>". ++$sl." )</b>  $weight_package->name <br>";
                        }
                    }
                    return $html;
                })
                ->addColumn('packageRate', function($data){
                    $html = "";
                    if($data->weight_packages->count() > 0){
                        $sl = 0;
                        foreach($data->weight_packages as $weight_package){
                            $html .= $weight_package->pivot->rate." <br>";
                        }
                    }
                    return $html;
                })
                ->addColumn('action', function($data){
                    $button = '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.serviceAreaSetting.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" service_area_setting_id="'.$data->id.'">
                                <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'weightPackage','packageRate', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceAreaSetting';
        $data['page_title'] = 'Create Service Area Setting';
        $data['serviceAreas'] = ServiceArea::where([['status','=', 1], ['weight_type','=', 1]])->get();
        $data['weightPackages'] = WeightPackage::where([['status','=', 1], ['weight_type','=', 1]])->get();
        return view('admin.applicationSetting.serviceAreaSetting.create', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'service_area_id'       => 'required|numeric|unique:service_area_settings',
            'weight_package_id'     => 'required',
            'weight_package_id.*'   => 'required|numeric',
            'rate'                  => 'required',
            'rate.*'                => 'required|numeric',
        ], [
            'service_area_id.unique' => 'This Service Area Has been already Exist..',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'service_area_id'         => $request->input('service_area_id'),
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $serviceAreaSetting = ServiceAreaSetting::create($data);

        if ($serviceAreaSetting) {
            $service_area_id    = $request->input('service_area_id');
            $weight_package_id  = $request->input('weight_package_id');
            $rate               = $request->input('rate');

            $sync_data = [];

            for ($i = 0; $i < count($weight_package_id); $i++) {
                $sync_data[$weight_package_id[$i]] = [
                    'service_area_setting_id'   => $serviceAreaSetting->id,
                    'service_area_id'           => $service_area_id,
                    'rate'                      => $rate[$i],
                ];
            }
            $serviceAreaSetting->weight_packages()->sync($sync_data);

            $this->setMessage('Service Area Setting Create Successfully', 'success');
            return redirect()->route('admin.serviceAreaSetting.index');
        } else {
            $this->setMessage('Service Area Setting Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, ServiceAreaSetting $serviceAreaSetting) {
        return view('admin.applicationSetting.serviceAreaSetting.show', compact('serviceAreaSetting'));
    }

    public function edit(Request $request, ServiceAreaSetting $serviceAreaSetting) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceAreaSetting';
        $data['page_title'] = 'Edit Service Area Setting';
        $data['serviceAreas'] = ServiceArea::where([['status','=', 1], ['weight_type','=', 1]])->get();
        $data['weightPackages'] = WeightPackage::where([['status','=', 1], ['weight_type','=', 1]])->get();
        $data['serviceAreaSetting']      =  $serviceAreaSetting->load('weight_packages');;
        return view('admin.applicationSetting.serviceAreaSetting.edit', $data);
    }

    public function update(Request $request, ServiceAreaSetting $serviceAreaSetting) {
        $validator = Validator::make($request->all(), [
            'service_area_id'       => 'required|numeric|unique:service_area_settings,service_area_id,'.$serviceAreaSetting->id,
            'weight_package_id'     => 'required',
            'weight_package_id.*'   => 'required|numeric',
            'rate'                  => 'required',
            'rate.*'                => 'required|numeric',
        ], [
            'service_area_id.unique' => 'This Service Area Has been already Exist..',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'service_area_id'     => $request->input('service_area_id'),
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = ServiceAreaSetting::where('id', $serviceAreaSetting->id)->update($data) ? true : false;

        if ($check) {
            $serviceAreaSetting = ServiceAreaSetting::where('id', $serviceAreaSetting->id)->first();

            $service_area_id    = $request->input('service_area_id');
            $weight_package_id  = $request->input('weight_package_id');
            $rate               = $request->input('rate');

            $sync_data = [];

            for ($i = 0; $i < count($weight_package_id); $i++) {
                $sync_data[$weight_package_id[$i]] = [
                    'service_area_setting_id'   => $serviceAreaSetting->id,
                    'service_area_id'           => $service_area_id,
                    'rate'                      => $rate[$i],
                ];
            }
            $serviceAreaSetting->weight_packages()->sync($sync_data);

            $this->setMessage('Service Area Update Successfully', 'success');
            return redirect()->route('admin.serviceAreaSetting.index');
        } else {
            $this->setMessage('Service Area Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'service_area_setting_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = ServiceAreaSetting::where('id', $request->service_area_setting_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Service Area Setting Status Update Successfully',
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

    public function destroy(Request $request, ServiceAreaSetting $serviceAreaSetting) {
        $serviceAreaSetting->weight_packages()->detach();
        $check = $serviceAreaSetting->delete() ? true : false;

        if ($check) {
            $this->setMessage('Service Area Setting Delete Successfully', 'success');
        } else {
            $this->setMessage('Service Area Setting Delete Failed', 'danger');
        }
        return redirect()->route('admin.serviceArea.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'service_area_setting_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $serviceAreaSetting = ServiceAreaSetting::where('id', $request->service_area_setting_id)->first();
                $serviceAreaSetting->weight_packages()->detach();
                $check      = $serviceAreaSetting->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Service Area Setting Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

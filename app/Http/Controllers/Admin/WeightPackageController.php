<?php

namespace App\Http\Controllers\Admin;

use App\Models\WeightPackage;
use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class WeightPackageController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'weightPackage';
        $data['page_title'] = 'Weight Packages';
        return view('admin.applicationSetting.weightPackage.index', $data);
    }

    public function getWeightPackages(Request $request){
        $model  = WeightPackage::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('details', function($data){
                    return substr($data->details,0,60);
                })
                ->editColumn('rate', function($data){
                    return number_format($data->rate, 2);
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" weight_package_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->editColumn('weight_type', function($data){
                    if($data->weight_type  == 1){
                        $weight_type =  "KG";
                    }
                    else{
                        $weight_type =  "CFT";
                    }
                    return $weight_type;
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" weight_package_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.weightPackage.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" weight_package_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['details','rate', 'weight_type', 'status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'weightPackage';
        $data['page_title'] = 'Create Weight Package';
        return view('admin.applicationSetting.weightPackage.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'title'    => 'required',
            'weight_type'    => 'required',
            'details'  => 'sometimes',
            'rate'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'         => $request->input('name'),
            'wp_id'         => $this->returnUniqueWeightPackageID(),
            'title'       => $request->input('title'),
            'weight_type'       => $request->input('weight_type'),
            'details'       => $request->input('details'),
            'rate'         => $request->input('rate'),
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = WeightPackage::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Weight Package Create Successfully', 'success');
            return redirect()->route('admin.weightPackage.index');
        } else {
            $this->setMessage('Weight Package Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, WeightPackage $weightPackage) {
        return view('admin.applicationSetting.weightPackage.show', compact('weightPackage'));
    }

    public function edit(Request $request, WeightPackage $weightPackage) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'weightPackage';
        $data['page_title'] = 'Edit Weight Package';
        $data['weightPackage']      = $weightPackage;
        return view('admin.applicationSetting.weightPackage.edit', $data);
    }

    public function update(Request $request, WeightPackage $weightPackage) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'title'     => 'required',
            'details'   => 'sometimes',
            'rate'      => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
         

        $data = [
            'name'              => $request->input('name'),
            'title'             => $request->input('title'),
            'details'           => $request->input('details'),
            'weight_type'       => $request->input('weight_type'),
            'rate'              => $request->input('rate'),
            'updated_admin_id'  => auth()->guard('admin')->user()->id,
        ];

        $check = WeightPackage::where('id', $weightPackage->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Weight Package Update Successfully', 'success');
            return redirect()->route('admin.weightPackage.index');
        } else {
            $this->setMessage('Weight Package Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'weight_package_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = WeightPackage::where('id', $request->weight_package_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Weight Package Status Update Successfully',
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

    public function destroy(Request $request, WeightPackage $weightPackage) {

        $check = $weightPackage->delete() ? true : false;

        if ($check) {
            $this->setMessage('Weight Package Delete Successfully', 'success');
        } else {
            $this->setMessage('Weight Package Delete Failed', 'danger');
        }
        return redirect()->route('admin.weightPackage.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'weight_package_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = WeightPackage::where('id', $request->weight_package_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Weight Package Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function weightPackageOption(Request $request) {

        if ($request->ajax()) {
            $option         = '<option value="0" data-charge="0">Select Weight Package </option>';

            $district    = District::where('id', $request->district_id)->first();

            if(!empty($district)){
                $service_area_id  = $district->service_area_id;

                $weightPackages  = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    }])
                    ->where([
                        ['status','=', 1],
                    ])
                    ->orderBy('weight_type', 'asc')
                    ->get();

                foreach ($weightPackages as $weightPackage){
                    $rate = $weightPackage->rate;
                    if(!empty($weightPackage->service_area)){
                        $rate = $weightPackage->service_area->rate;
                    }

                    $option .= '<option  value="'.$weightPackage->id.'"
                    data-charge="'.$rate.'">
                        '.$weightPackage->name .'
                    </option>';
                }
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }

}

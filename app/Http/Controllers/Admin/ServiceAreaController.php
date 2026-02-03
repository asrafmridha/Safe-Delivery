<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceArea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class ServiceAreaController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceArea';
        $data['page_title'] = 'Service Areas';
        return view('admin.applicationSetting.serviceArea.index', $data);
    }

    public function getServiceAreas(Request $request){
        $model  = ServiceArea::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('default_charge', function($data){
                    return number_format($data->default_charge,2);
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
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" service_area_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" service_area_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.serviceArea.edit', $data->id).'" class="btn btn-success btn-sm "> <i class="fa fa-edit"></i> </a>';
                    // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm  delete-btn" service_area_id="'.$data->id.'"><i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['weight_type', 'status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceArea';
        $data['page_title'] = 'Create Service Area';
        return view('admin.applicationSetting.serviceArea.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'details'  => 'sometimes',
            'weight_type'    => 'required',
            'cod_charge'  => 'sometimes',
            'default_charge'  => 'sometimes',
            'delivery_time'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'         => $request->input('name'),
            'cod_charge'         => $request->input('cod_charge') ?? 0,
            'default_charge'         => $request->input('default_charge') ?? 0,
            'delivery_time'         => $request->input('delivery_time'),
            'weight_type'         => $request->input('weight_type'),
            'details'       => $request->input('details'),
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = ServiceArea::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Service Area Create Successfully', 'success');
            return redirect()->route('admin.serviceArea.index');
        } else {
            $this->setMessage('Service Area Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, ServiceArea $serviceArea) {
        return view('admin.applicationSetting.serviceArea.show', compact('serviceArea'));
    }

    public function edit(Request $request, ServiceArea $serviceArea) {
        $data               = [];
        $data['main_menu']  = 'applicationSetting';
        $data['child_menu'] = 'serviceArea';
        $data['page_title'] = 'Edit Service Area';
        $data['serviceArea']      = $serviceArea;
        return view('admin.applicationSetting.serviceArea.edit', $data);
    }

    public function update(Request $request, ServiceArea $serviceArea) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'details'  => 'sometimes',
            'weight_type'    => 'required',
            'cod_charge'  => 'sometimes',
            'delivery_time'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'         => $request->input('name'),
            'cod_charge'         => $request->input('cod_charge') ?? 0,
            'default_charge'         => $request->input('default_charge') ?? 0,
            'delivery_time'         => $request->input('delivery_time'),
            'weight_type'         => $request->input('weight_type'),
            'details'       => $request->input('details'),
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = ServiceArea::where('id', $serviceArea->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Service Area Update Successfully', 'success');
            return redirect()->route('admin.serviceArea.index');
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
                'service_area_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = ServiceArea::where('id', $request->service_area_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Service Area Status Update Successfully',
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

    public function destroy(Request $request, ServiceArea $serviceArea) {

        $check = $serviceArea->delete() ? true : false;

        if ($check) {
            $this->setMessage('Service Area Delete Successfully', 'success');
        } else {
            $this->setMessage('Service Area Delete Failed', 'danger');
        }
        return redirect()->route('admin.serviceArea.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'service_area_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = ServiceArea::where('id', $request->service_area_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Service Area Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

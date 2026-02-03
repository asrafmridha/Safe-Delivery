<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use App\Models\ServiceType;
use App\Models\Unit;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'service-type-list';
        $data['page_title'] = 'Service Type List';
        $data['collapse'] = 'sidebar-collapse';
        return view('admin.traditionalParcelSetting.service_type.index', $data);
    }

    public function datatable(Request $request)
    {
        $model = ServiceType::select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('service_area', function ($data) {
                return $data->service_area->name;
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
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" data_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '&nbsp;&nbsp;&nbsp; <a href="' . route('admin.service.type.edit', $data->id) . '" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" data_id="' . $data->id . '"><i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['status', 'service_area', 'action'])
            ->make(true);
    }
    public function print(Request $request)
    {
        $serviceTypes = ServiceType::all();
        return view('admin.traditionalParcelSetting.service_type.print', compact('serviceTypes'));
    }

    public function create()
    {
        $data = [];
        $data['serviceAreas'] = ServiceArea::all();
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'service-type-list';
        $data['page_title'] = 'Service Type';
        return view('admin.traditionalParcelSetting.service_type.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'rate' => 'required',
                'service_area_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = [
                'title' => $request->input('title'),
                'rate' => $request->input('rate'),
                'service_area_id' => $request->input('service_area_id'),
                'created_admin_id' => auth()->guard('admin')->user()->id,
            ];

            ServiceType::create($data);
            $this->setMessage('Service Type Create Successfully', 'success');
            return redirect()->route('admin.service.type');
        } catch (\Exception $exception) {
            $this->setMessage('Service Type Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function edit(Request $request, $id)
    {
        $data = [];
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'service-type-list';
        $data['page_title'] = 'Service Type';
        $data['serviceType'] = ServiceType::find($id);
        $data['serviceAreas'] = ServiceArea::all();
        return view('admin.traditionalParcelSetting.service_type.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'rate' => 'required',
                'service_area_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = [
                'title' => $request->input('title'),
                'rate' => $request->input('rate'),
                'service_area_id' => $request->input('service_area_id'),
                'updated_admin_id' => auth()->guard('admin')->user()->id,
            ];
            $check = ServiceType::where('id', $id)->update($data) ? true : false;


            $this->setMessage('Service Type Update Successfully', 'success');
            return redirect()->route('admin.service.type');
        } catch (\Exception $exception) {
            $this->setMessage($exception->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'data_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            } else {
                $check = ServiceType::where('id', $request->input('data_id'))->update(['status' => $request->input("status")]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Service Type Status Update Successfully',
                        'status' => $request->input('status'),
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

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'data_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      =  ServiceType::where('id', $request->data_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Service Type Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }
}

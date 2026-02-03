<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller {

    public function index() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'vehicle';
        $data['page_title'] = 'Vehicle List';
        $data['collapse']   = 'sidebar-collapse';
        return view('admin.traditionalParcelSetting.vehicle.index', $data);
    }

    public function getVehicles(Request $request) {
        $model = Vehicle::all();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" vehicle_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {

                //   $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" vehicle_id="' . $data->id . '}" >
                //   <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button = '<a href="' . route('admin.vehicle.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" vehicle_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function printVehicles(Request $request) {
        $vehicles = Vehicle::all();
        return view('admin.traditionalParcelSetting.vehicle.print', compact('vehicles'));

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" vehicle_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {

                //   $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" vehicle_id="' . $data->id . '}" >
                //   <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button = '<a href="' . route('admin.vehicle.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" vehicle_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'vehicle';
        $data['page_title'] = 'Create Vehicle';
        return view('admin.traditionalParcelSetting.vehicle.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'vehicle_sl_no'        => 'required',
            'vehicle_no'           => 'required|unique:vehicles',
            'vehicle_driver_name'  => 'required',
            'vehicle_driver_phone' => 'required',
            'vehicle_road'         => 'required',
        ], [
            'vehicle_no.unique' => 'This Vehicle Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                 => $request->input('name'),
            'vehicle_sl_no'        => $request->input('vehicle_sl_no'),
            'vehicle_no'           => $request->input('vehicle_no'),
            'vehicle_driver_name'  => $request->input('vehicle_driver_name'),
            'vehicle_driver_phone' => $request->input('vehicle_driver_phone'),
            'vehicle_road'         => $request->input('vehicle_road'),
            'created_admin_id'     => auth()->guard('admin')->user()->id,
        ];
        $check = Vehicle::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Vehicle Create Successfully', 'success');
            return redirect()->route('admin.vehicle.index');
        } else {
            $this->setMessage('Vehicle Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function edit(Request $request, Vehicle $vehicle) {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'vehicle';
        $data['page_title'] = 'Edit Vehicle';
        $data['vehicle']    = $vehicle;
        return view('admin.traditionalParcelSetting.vehicle.edit', $data);
    }

    public function update(Request $request, Vehicle $vehicle) {
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'vehicle_sl_no'        => 'required',
            'vehicle_no'           => 'required|unique:vehicles,vehicle_no,' . $vehicle->id,
            'vehicle_driver_name'  => 'required',
            'vehicle_driver_phone' => 'required',
            'vehicle_road'         => 'required',
        ], [
            'vehicle_no.unique' => 'This Vehicle Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                 => $request->input('name'),
            'vehicle_sl_no'        => $request->input('vehicle_sl_no'),
            'vehicle_no'           => $request->input('vehicle_no'),
            'vehicle_driver_name'  => $request->input('vehicle_driver_name'),
            'vehicle_driver_phone' => $request->input('vehicle_driver_phone'),
            'vehicle_road'         => $request->input('vehicle_road'),
            'updated_admin_id'     => auth()->guard('admin')->user()->id,
        ];

        $check = Vehicle::where('id', $vehicle->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Vehicle Update Successfully', 'success');
            return redirect()->route('admin.vehicle.index');
        } else {
            $this->setMessage('Vehicle Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'vehicle_id' => 'required',
                'status'     => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Vehicle::where('id', $request->vehicle_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Vehicle Status Update Successfully',
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

    public function destroy(Request $request, Vehicle $vehicle) {
        $check = $vehicle->delete() ? true : false;

        if ($check) {
            $this->setMessage('Vehicle Delete Successfully', 'success');
        } else {
            $this->setMessage('Vehicle Delete Failed', 'danger');
        }

        return redirect()->route('admin.vehicle.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'vehicle_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check  = Vehicle::where('id', $request->vehicle_id)->delete() ? true : false;
                if ($check) {
                    $response = ['success' => 'Vehicle Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

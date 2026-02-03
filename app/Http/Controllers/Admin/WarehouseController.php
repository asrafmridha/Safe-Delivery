<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Warehouse;

class WarehouseController extends Controller {

    public function index() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'warehouse';
        $data['page_title'] = 'Warehouse List';
        $data['collapse']   = 'sidebar-collapse';
        return view('admin.team.warehouse.index', $data);
    }

    public function getWarehouses(Request $request) {
        $model = Warehouse::all();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('type', function ($data) {
                if ($data->type == 1) {
                    $status_name = "Division";
                } else {
                    $status_name = "District";
                }
                return $status_name;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" warehouse_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {

                $button = '<a href="' . route('admin.warehouse.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" warehouse_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }
    public function printWarehouses(Request $request) {
        $warehouses = Warehouse::all();
        return view('admin.team.warehouse.print', compact('warehouses'));
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'warehouse';
        $data['page_title'] = 'Create Warehouse';
        return view('admin.team.warehouse.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|unique:warehouses',
            'type'         => 'required',
        ], [
            'name.unique' => 'This Warehouse Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                 => $request->input('name'),
            'type'         => $request->input('type'),
            'created_admin_id'     => auth()->guard('admin')->user()->id,
        ];
        // dd($data);
        $check = Warehouse::create($data) ? true : false;

        if ($check) {
            $this->adminDashboardCounterEvent();
            $this->setMessage('Warehouse Create Successfully', 'success');
            return redirect()->route('admin.warehouse.index');
        } else {
            $this->setMessage('Warehouse Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request, Warehouse $warehouse) {
        $data               = [];
        $data['main_menu']  = 'team';
        $data['child_menu'] = 'warehouse';
        $data['page_title'] = 'Edit Warehouse';
        $data['warehouse']    = $warehouse;
        return view('admin.team.warehouse.edit', $data);
    }

    public function update(Request $request, Warehouse $warehouse) {

        $validator = Validator::make($request->all(), [
            'name'           => 'required|unique:warehouses,name,' . $warehouse->id,
            'type'  => 'required',
        ], [
            'name.unique' => 'This Warehouse Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                 => $request->input('name'),
            'type'         => $request->input('type'),
            'updated_admin_id'     => auth()->guard('admin')->user()->id,
        ];

        $check = Warehouse::where('id', $warehouse->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Warehouse Update Successfully', 'success');
            return redirect()->route('admin.warehouse.index');
        } else {
            $this->setMessage('Warehouse Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'warehouse_id' => 'required',
                'status'     => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Warehouse::where('id', $request->warehouse_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Warehouse Status Update Successfully',
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

    public function destroy(Request $request, Warehouse $warehouse) {
        $check = $warehouse->delete() ? true : false;

        if ($check) {
            $this->setMessage('Warehouse Delete Successfully', 'success');
        } else {
            $this->setMessage('Warehouse Delete Failed', 'danger');
        }

        return redirect()->route('admin.warehouse.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'warehouse_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check  = Warehouse::where('id', $request->warehouse_id)->delete() ? true : false;
                if ($check) {

                    $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Warehouse Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

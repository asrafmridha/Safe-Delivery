<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'warehouse';
        $data['page_title'] = 'Warehouse List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.traditionalParcelSetting.warehouse.index', $data);
    }

    public function getWarehouses(Request $request) {
        $model = Warehouse::all();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('type', function ($data) {
                if ($data->type == 1) {
                    $type = "Division";
                } else{
                    $type = "District";
                }
                return $type;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" wh_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                return "";
            })
            ->rawColumns(['type', 'status', 'action', 'image'])
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
        $data['main_menu']  = 'warehouse';
        $data['child_menu'] = 'warehouse_list';
        $data['page_title'] = 'Create Warehouse';
        return view('admin.warehouse.create', $data);
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
            'wh_name'           => 'required|unique:warehouse_tbls',
        ], [
            'wh_name.unique' => 'This warehouse already exists',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'wh_name'              => $request->input('wh_name'),
            'wh_type'             => $request->input('wh_type'),
            'created_admin_id'          => auth()->guard('admin')->user()->id,
        ];
        $check = WarehouseTbl::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Warehouse Create Successfully', 'success');
            return redirect()->route('admin.warehouse.index');
        } else {
            $this->setMessage('Warehouse Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, WarehouseTbl $warehouse) {
        $data               = [];
        $data['main_menu']  = 'warehouse';
        $data['child_menu'] = 'warehouse_list';
        $data['page_title'] = 'Edit Warehouse';
        $data['warehouse']     = $warehouse;
        return view('admin.warehouse.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarehouseTbl $warehouse) {

        $validator = Validator::make($request->all(), [
            'wh_name'           => 'required|unique:warehouse_tbls,wh_name,'.$warehouse->id,
        ], [
            'wh_name.unique' => 'This warehouse already exists',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'wh_name'              => $request->input('wh_name'),
            'wh_type'             => $request->input('wh_type'),
            'updated_admin_id'          => auth()->guard('admin')->user()->id,
        ];

        $check = WarehouseTbl::where('id', $warehouse->id)->update($data) ? true : false;

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
                'wh_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = WarehouseTbl::where('id', $request->wh_id)->update(['status' => $request->status]) ? true : false;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, WarehouseTbl $warehouse) {
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
                'wh_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check  = WarehouseTbl::where('id', $request->wh_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Warehouse Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class UnitController extends Controller
{
    public function index() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'unit_list';
        $data['page_title'] = 'Unit';
        return view('admin.traditionalParcelSetting.unit.index', $data);
    }

    public function getUnits(Request $request){
        $model  = Unit::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" unit_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.unit.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" unit_id="'.$data->id.'">
                                <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function printUnits(Request $request){
        $units  = Unit::all();
        return view('admin.traditionalParcelSetting.unit.print', compact('units'));
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'unit_list';
        $data['page_title'] = 'Unit';
        return view('admin.traditionalParcelSetting.unit.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => ['required','unique:units'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                  => $request->input('name'),
            'created_admin_id'      => auth()->guard('admin')->user()->id,
        ];

        $check = Unit::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Unit Create Successfully', 'success');
            return redirect()->route('admin.unit.index');
            //return redirect()->back();
        } else {
            $this->setMessage('Unit Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request, Unit $Unit) {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'unit_list';
        $data['page_title'] = 'Unit';
        $data['Unit']      = $Unit;
        return view('admin.traditionalParcelSetting.unit.edit', $data);
    }

    public function update(Request $request, Unit $Unit) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'details'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'updated_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = Unit::where('id', $Unit->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Category Update Successfully', 'success');
            return redirect()->route('admin.unit.index');
        } else {
            $this->setMessage('Unit Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'unit_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Unit::where('id', $request->unit_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Unit Status Update Successfully',
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

    public function destroy(Request $request, ItemCategory $ItemCategory) {

        $check = $ItemCategory->delete() ? true : false;

        if ($check) {
            $this->setMessage('Item Category Delete Successfully', 'success');
        } else {
            $this->setMessage('Item Category Delete Failed', 'danger');
        }
        return redirect()->route('admin.ItemCategory.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'unit_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = Unit::where('id', $request->unit_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Unit Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }
}

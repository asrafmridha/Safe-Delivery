<?php

namespace App\Http\Controllers\Admin;

use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;

class DesignationController extends Controller
{
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'designation';
        $data['page_title'] = 'Designations';
        return view('admin.website.designation.index', $data);
    }

    public function getDesignations(Request $request){
        $model  = Designation::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" designation_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('admin.designation.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" designation_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'designation';
        $data['page_title'] = 'Create Designation';
        return view('admin.website.designation.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = Designation::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Designation Create Successfully', 'success');
            return redirect()->route('admin.designation.index');
        } else {
            $this->setMessage('Designation Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Designation $designation) {
        return view('admin.website.slider.show', compact('designation'));
    }

    public function edit(Request $request, Designation $designation) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'designation';
        $data['page_title'] = 'Edit Designation';
        $data['designation']= $designation;
        return view('admin.website.designation.edit', $data);
    }

    public function update(Request $request, Designation $designation) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $data = [
            'name'              => $request->input('name'),
            'updated_admin_id'  => auth()->guard('admin')->user()->id,
        ];

        $check = Designation::where('id', $designation->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Designation Update Successfully', 'success');
            return redirect()->route('admin.designation.index');
        } else {
            $this->setMessage('Designation Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'designation_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            }
            else{
                $check = Designation::where('id', $request->designation_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Designation Status Update Successfully',
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

    public function destroy(Request $request, Designation $designation) {
        $check = $designation->delete() ? true : false;

        if ($check) {
            $this->setMessage('Designation Delete Successfully', 'success');
        } else {
            $this->setMessage('Designation Delete Failed', 'danger');
        }
        return redirect()->route('admin.designation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request) {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'designation_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found 2'];
            }
            else{
                $check = Designation::where('id', $request->designation_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Designation Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }
}

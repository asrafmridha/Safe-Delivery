<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;
use App\Models\Office;


class OfficeController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'office';
        $data['page_title'] = 'Offices';
        return view('admin.website.office.index', $data);
    }

    public function getOffices(Request $request){
        $model  = Office::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" office_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.office.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" office_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'office';
        $data['page_title'] = 'Create Office';
        return view('admin.website.office.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'contact_number'  => 'required',
            'email'  => 'required',
            'address'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'         => $request->input('name'),
            'contact_number'         => $request->input('contact_number'),
            'email'         => $request->input('email'),
            'address'         => $request->input('address'),
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = Office::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Office Create Successfully', 'success');
            return redirect()->route('admin.office.index');
        } else {
            $this->setMessage('Office Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Office $office) {
        return view('admin.website.office.show', compact('office'));
    }

    public function edit(Request $request, Office $office) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'office';
        $data['page_title'] = 'Edit Office';
        $data['office']      = $office;
        return view('admin.website.office.edit', $data);
    }

    public function update(Request $request, Office $office) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'contact_number'  => 'required',
            'email'  => 'required',
            'address'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'         => $request->input('name'),
            'contact_number'       => $request->input('contact_number'),
            'email'       => $request->input('email'),
            'address'       => $request->input('address'),
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = Office::where('id', $office->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Office Update Successfully', 'success');
            return redirect()->route('admin.office.index');
        } else {
            $this->setMessage('Office Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'office_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Office::where('id', $request->office_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Office Status Update Successfully',
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

    public function destroy(Request $request, Office $office) {
        $check = $office->delete() ? true : false;
        if ($check) {
            $this->setMessage('Office Delete Successfully', 'success');
        } else {
            $this->setMessage('Office Delete Failed', 'danger');
        }
        return redirect()->route('admin.office.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'office_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $office    = Office::where('id', $request->office_id)->first();
                $check      = Office::where('id', $request->office_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Office Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }
}

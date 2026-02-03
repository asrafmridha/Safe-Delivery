<?php

namespace App\Http\Controllers\Admin;

use App\Models\ParcelStep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class ParcelStepController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Parcel Steps';
        return view('admin.website.parcelStep.index', $data);
    }

    public function getParcelSteps(Request $request){
        $model  = ParcelStep::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/parcelStep/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Parcel Step Image">';
                    }
                    return $image;
                })
                ->editColumn('short_details', function($data){
                    return substr($data->short_details,0,60);
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" parcel_step_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" parcel_step_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.parcelStep.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" parcel_step_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Create Parcel Step';
        return view('admin.website.parcelStep.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/parcelStep/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'title'         => $request->input('title'),
            'short_details'         => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = ParcelStep::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Parcel Step Create Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('Parcel Step Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, ParcelStep $parcelStep) {
        return view('admin.website.parcelStep.show', compact('parcelStep'));
    }

    public function edit(Request $request, ParcelStep $parcelStep) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Edit Parcel Step';
        $data['parcelStep']      = $parcelStep;
        return view('admin.website.parcelStep.edit', $data);
    }

    public function update(Request $request, ParcelStep $parcelStep) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/parcelStep/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($parcelStep->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/parcelStep/' . $parcelStep->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $parcelStep->image;
        }

        $data = [
            'title'         => $request->input('title'),
            'short_details'       => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = ParcelStep::where('id', $parcelStep->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Parcel Step Update Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('Parcel Step Update Failed', 'danger');
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
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_step_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = ParcelStep::where('id', $request->parcel_step_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Parcel Step Status Update Successfully',
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

    public function destroy(Request $request, ParcelStep $parcelStep) {

        if (!empty($parcelStep->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/parcelStep/' . $parcelStep->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $parcelStep->delete() ? true : false;

        if ($check) {
            $this->setMessage('Parcel Step Delete Successfully', 'success');
        } else {
            $this->setMessage('Parcel Step Delete Failed', 'danger');
        }
        return redirect()->route('admin.parcelStep.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_step_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $parcelStep    = ParcelStep::where('id', $request->parcel_step_id)->first();
                $check      = ParcelStep::where('id', $request->parcel_step_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($parcelStep->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/parcelStep/' . $parcelStep->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Parcel Step Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

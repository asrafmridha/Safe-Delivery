<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class SliderController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'slider';
        $data['page_title'] = 'Sliders';
        return view('admin.website.slider.index', $data);
    }

    public function getSliders(Request $request){
        $model  = Slider::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/slider/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Slider Image">';
                    }
                    return $image;
                })
                ->editColumn('details', function($data){
                    return substr($data->details,0,60);
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" slider_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" slider_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.slider.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" slider_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'slider';
        $data['page_title'] = 'Create Slider';
        return view('admin.website.slider.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'    => 'sometimes',
            'details'  => 'sometimes',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/slider/' . $photo_name;
            Image::make($photo)->save($photo_path);
        } else {
            $photo_name = null;
        }

        $data = [
            'title'         => $request->input('title'),
            'details'       => $request->input('details'),
            'image'         => $photo_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = Slider::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Slider Create Successfully', 'success');
            return redirect()->route('admin.slider.index');
        } else {
            $this->setMessage('Slider Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Slider $slider) {
        return view('admin.website.slider.show', compact('slider'));
    }

    public function edit(Request $request, Slider $slider) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'slider';
        $data['page_title'] = 'Edit Slider';
        $data['slider']      = $slider;
        return view('admin.website.slider.edit', $data);
    }

    public function update(Request $request, Slider $slider) {
        $validator = Validator::make($request->all(), [
            'title'    => 'sometimes',
            'details'  => 'sometimes',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/slider/' . $photo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($slider->image)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/slider/' . $slider->image;

                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        else{
            $photo_name = $slider->image;
        }

        $data = [
            'title'         => $request->input('title'),
            'details'       => $request->input('details'),
            'image'         => $photo_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = Slider::where('id', $slider->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Slider Update Successfully', 'success');
            return redirect()->route('admin.slider.index');
        } else {
            $this->setMessage('Slider Update Failed', 'danger');
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
                'slider_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Slider::where('id', $request->slider_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Slider Status Update Successfully',
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

    public function destroy(Request $request, Slider $slider) {

        if (!empty($slider->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/slider/' . $slider->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $slider->delete() ? true : false;

        if ($check) {
            $this->setMessage('Admin Delete Successfully', 'success');
        } else {
            $this->setMessage('Admin Delete Failed', 'danger');
        }
        return redirect()->route('admin.admin.index');
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
                'slider_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $slider = Slider::where('id', $request->slider_id)->first();
                $check = Slider::where('id', $request->slider_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($slider->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/slider/' . $slider->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Slider Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

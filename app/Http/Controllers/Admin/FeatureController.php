<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class FeatureController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'feature';
        $data['page_title'] = 'Features';
        return view('admin.website.feature.index', $data);
    }

    public function getFeatures(Request $request){
        $model  = Feature::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/feature/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Feature Image">';
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" feature_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" feature_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.feature.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" feature_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'feature';
        $data['page_title'] = 'Create Feature';
        return view('admin.website.feature.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'heading'    => 'required',
            'details'  => 'required',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/feature/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'title'         => $request->input('title'),
            'heading'         => $request->input('heading'),
            'details'       => $request->input('details'),
            'image'         => $image_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = Feature::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Feature Create Successfully', 'success');
            return redirect()->route('admin.feature.index');
        } else {
            $this->setMessage('Feature Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Feature $feature) {
        return view('admin.website.feature.show', compact('feature'));
    }

    public function edit(Request $request, Feature $feature) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'feature';
        $data['page_title'] = 'Edit Feature';
        $data['feature']      = $feature;
        return view('admin.website.feature.edit', $data);
    }

    public function update(Request $request, Feature $feature) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'heading'    => 'required',
            'details'  => 'required',
            'image'    => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/feature/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($feature->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/feature/' . $feature->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $feature->image;
        }

        $data = [
            'title'         => $request->input('title'),
            'heading'       => $request->input('heading'),
            'details'       => $request->input('details'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = Feature::where('id', $feature->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Feature Update Successfully', 'success');
            return redirect()->route('admin.feature.index');
        } else {
            $this->setMessage('Feature Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'feature_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Feature::where('id', $request->feature_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Feature Status Update Successfully',
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

    public function destroy(Request $request, Feature $feature) {

        if (!empty($feature->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/feature/' . $feature->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $feature->delete() ? true : false;

        if ($check) {
            $this->setMessage('Feature Delete Successfully', 'success');
        } else {
            $this->setMessage('Feature Delete Failed', 'danger');
        }
        return redirect()->route('admin.feature.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'feature_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $feature    = Feature::where('id', $request->feature_id)->first();
                $check      = Feature::where('id', $request->feature_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($feature->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/feature/' . $feature->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Feature Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

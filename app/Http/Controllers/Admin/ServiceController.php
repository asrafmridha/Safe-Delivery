<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class ServiceController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'service';
        $data['page_title'] = 'Services';
        return view('admin.website.service.index', $data);
    }

    public function getServices(Request $request){
        $model  = Service::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/service/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Service Image">';
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" service_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" service_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.service.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" service_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'service';
        $data['page_title'] = 'Create Service';
        return view('admin.website.service.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:services',
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'required|image',
            'icon'  => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/service/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        if ($request->hasFile('icon')) {
            $icon      = $request->file('icon');
            $icon_name = time() . str_random() . rand(1, 10000) . '.' . $icon->getClientOriginalExtension();
            $icon_path = 'public/uploads/service/' . $icon_name;
            Image::make($icon)->save($icon_path);
        } else {
            $icon_name = null;
        }

        $data = [
            'name'         => $request->input('name'),
            'slug'         => str_slug($request->input('name')),
            'short_details'         => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'icon'         => $icon_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = Service::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Service Create Successfully', 'success');
            return redirect()->route('admin.service.index');
        } else {
            $this->setMessage('Service Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Service $service) {
        return view('admin.website.service.show', compact('service'));
    }

    public function edit(Request $request, Service $service) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'service';
        $data['page_title'] = 'Edit Service';
        $data['service']      = $service;
        return view('admin.website.service.edit', $data);
    }

    public function update(Request $request, Service $service) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:services,name,' . $service->id,
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'sometimes|image',
            'icon'  => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/service/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($service->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/service/' . $service->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $service->image;
        }

        $delete_icon = $request->input('delete_icon');
        if(isset($delete_icon) && ($delete_icon == 1)){
            $old_icon_path = str_replace('\\', '/', public_path()) . '/uploads/service/' . $service->icon;
            if (file_exists($old_icon_path)) {
                unlink($old_icon_path);
            }
            $icon_name = null;
        }
        else{
            if ($request->hasFile('icon')) {
                $icon      = $request->file('icon');
                $icon_name = time() . str_random() . rand(1, 10000) . '.' . $icon->getClientOriginalExtension();
                $icon_path = 'public/uploads/service/' . $icon_name;
                Image::make($icon)->save($icon_path);

                if (!empty($service->icon)) {
                    $old_icon_path = str_replace('\\', '/', public_path()) . '/uploads/service/' . $service->icon;
                    if (file_exists($old_icon_path)) {
                        unlink($old_icon_path);
                    }
                }
            }
            else{
                $icon_name = $service->icon;
            }
        }


        $data = [
            'name'         => $request->input('name'),
            'slug'         => str_slug($request->input('name')),
            'short_details'       => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'icon'         => $icon_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = Service::where('id', $service->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Service Update Successfully', 'success');
            return redirect()->route('admin.service.index');
        } else {
            $this->setMessage('Service Update Failed', 'danger');
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
                'service_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Service::where('id', $request->service_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Service Status Update Successfully',
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

    public function destroy(Request $request, Service $service) {

        if (!empty($service->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $service->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }
        if (!empty($service->icon)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $service->icon;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $service->delete() ? true : false;

        if ($check) {
            $this->setMessage('Service Delete Successfully', 'success');
        } else {
            $this->setMessage('Service Delete Failed', 'danger');
        }
        return redirect()->route('admin.service.index');
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
                'service_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $service    = Service::where('id', $request->service_id)->first();
                $check      = Service::where('id', $request->service_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($service->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/service/' . $service->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }
                    if (!empty($service->icon)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/service/' . $service->icon;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Service Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;
use App\Models\DeliveryService;


class DeliveryServiceController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'deliveryService';
        $data['page_title'] = 'Delivery Services';
        return view('admin.website.deliveryService.index', $data);
    }

    public function getDeliveryServices(Request $request){
        $model  = DeliveryService::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/deliveryService/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Delivery Service Image">';
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" delivery_service_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" delivery_service_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.deliveryService.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" delivery_service_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'deliveryService';
        $data['page_title'] = 'Create Delivery Service';
        return view('admin.website.deliveryService.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:delivery_services',
            'short_details'  => 'required',
            'long_details'  => 'sometimes',
            'image'    => 'required|image',
            'icon'  => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/deliveryService/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        if ($request->hasFile('icon')) {
            $icon      = $request->file('icon');
            $icon_name = time() . str_random() . rand(1, 10000) . '.' . $icon->getClientOriginalExtension();
            $icon_path = 'public/uploads/deliveryService/' . $icon_name;
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
        $check = DeliveryService::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Delivery Service Create Successfully', 'success');
            return redirect()->route('admin.deliveryService.index');
        } else {
            $this->setMessage('Delivery Service Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, DeliveryService $deliveryService) {
        return view('admin.website.deliveryService.show', compact('deliveryService'));
    }

    public function edit(Request $request, DeliveryService $deliveryService) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'deliveryService';
        $data['page_title'] = 'Edit Delivery Service';
        $data['deliveryService']      = $deliveryService;
        return view('admin.website.deliveryService.edit', $data);
    }

    public function update(Request $request, DeliveryService $deliveryService) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:services,name,' . $deliveryService->id,
            'short_details'  => 'required',
            'long_details'  => 'sometimes',
            'image'    => 'sometimes|image',
            'icon'  => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/deliveryService/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($deliveryService->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $deliveryService->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $deliveryService->image;
        }

        //Icon
        $delete_icon = $request->input('delete_icon');
        if(isset($delete_icon) && ($delete_icon == 1)){
            $old_icon_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $deliveryService->icon;
            if (file_exists($old_icon_path)) {
                unlink($old_icon_path);
            }
            $icon_name = null;
        }
        else{
            if ($request->hasFile('icon')) {
                $icon      = $request->file('icon');
                $icon_name = time() . str_random() . rand(1, 10000) . '.' . $icon->getClientOriginalExtension();
                $icon_path = 'public/uploads/deliveryService/' . $icon_name;
                Image::make($icon)->save($icon_path);

                if (!empty($deliveryService->icon)) {
                    $old_icon_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $deliveryService->icon;
                    if (file_exists($old_icon_path)) {
                        unlink($old_icon_path);
                    }
                }
            }
            else{
                $icon_name = $deliveryService->icon;
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

        $check = DeliveryService::where('id', $deliveryService->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Delivery Service Update Successfully', 'success');
            return redirect()->route('admin.deliveryService.index');
        } else {
            $this->setMessage('Delivery Service Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'delivery_service_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = deliveryService::where('id', $request->delivery_service_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Delivery Service Status Update Successfully',
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

    public function destroy(Request $request, DeliveryService $deliveryService) {

        if (!empty($deliveryService->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $deliveryService->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }
        $check = $deliveryService->delete() ? true : false;

        if ($check) {
            $this->setMessage('Delivery Service Delete Successfully', 'success');
        } else {
            $this->setMessage('Delivery Service Delete Failed', 'danger');
        }
        return redirect()->route('admin.deliveryService.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'delivery_service_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $deliveryService    = deliveryService::where('id', $request->delivery_service_id)->first();
                $check      = deliveryService::where('id', $request->delivery_service_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($deliveryService->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/deliveryService/' . $deliveryService->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }
                    $response = [ 'success' => 'Delivery Service Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

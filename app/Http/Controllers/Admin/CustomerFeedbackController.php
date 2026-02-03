<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomerFeedback;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class CustomerFeedbackController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'customerFeedback';
        $data['page_title'] = 'Customer Feedbacks';
        return view('admin.website.customerFeedback.index', $data);
    }

    public function getCustomerFeedbacks(Request $request){
        $model  = CustomerFeedback::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/customerFeedback/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Customer Feedback Image">';
                    }
                    return $image;
                })
                ->editColumn('feedback', function($data){
                    return substr($data->feedback,0,60);
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" customer_feedback_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" customer_feedback_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.customerFeedback.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" customer_feedback_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['page_type', 'short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'customerFeedback';
        $data['page_title'] = 'Create Customer Feedback';
        return view('admin.website.customerFeedback.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'company'  => 'required',
            'feedback'  => 'required',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/customerFeedback/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'name'         => $request->input('name'),
            'company'     => $request->input('company'),
            'feedback'      => $request->input('feedback'),
            'image'             => $image_name,
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = CustomerFeedback::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Customer Feedback Create Successfully', 'success');
            return redirect()->route('admin.customerFeedback.index');
        } else {
            $this->setMessage('Customer Feedback Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, CustomerFeedback $customerFeedback) {
        return view('admin.website.customerFeedback.show', compact('customerFeedback'));
    }

    public function edit(Request $request, CustomerFeedback $customerFeedback) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'customerFeedback';
        $data['page_title'] = 'Edit Customer Feedback';
        $data['customerFeedback']      = $customerFeedback;
        return view('admin.website.customerFeedback.edit', $data);
    }

    public function update(Request $request, CustomerFeedback $customerFeedback) {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'company'  => 'required',
            'feedback'  => 'required',
            'image'         => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/customerFeedback/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($customerFeedback->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/customerFeedback/' . $customerFeedback->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $customerFeedback->image;
        }

        $data = [
            'name'       => $request->input('name'),
            'company'       => $request->input('company'),
            'feedback'       => $request->input('feedback'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = CustomerFeedback::where('id', $customerFeedback->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Customer Feedback Update Successfully', 'success');
            return redirect()->route('admin.customerFeedback.index');
        } else {
            $this->setMessage('Customer Feedback Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'customer_feedback_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = CustomerFeedback::where('id', $request->customer_feedback_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Customer Feedback Status Update Successfully',
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

    public function destroy(Request $request, CustomerFeedback $customerFeedback) {

        if (!empty($customerFeedback->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/customerFeedback/' . $customerFeedback->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $customerFeedback->delete() ? true : false;

        if ($check) {
            $this->setMessage('Customer Feedback Delete Successfully', 'success');
        } else {
            $this->setMessage('Customer Feedback Delete Failed', 'danger');
        }
        return redirect()->route('admin.customerFeedback.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'customer_feedback_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $customerFeedback    = CustomerFeedback::where('id', $request->customer_feedback_id)->first();
                $check      = CustomerFeedback::where('id', $request->customer_feedback_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($customerFeedback->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/customerFeedback/' . $customerFeedback->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Customer Feedback Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

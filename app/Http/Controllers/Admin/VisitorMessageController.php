<?php

namespace App\Http\Controllers\Admin;

use App\Models\VisitorMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class VisitorMessageController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'visitorMessage';
        $data['page_title'] = 'Visitor Messages';
        return view('admin.website.visitorMessage.index', $data);
    }

    public function getVisitorMessages(Request $request){
        $model  = VisitorMessage::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('message', function($data){
                    return substr($data->message,0,60);
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "danger"; $status = 0; $status_name = "Unread";
                    }
                    else{
                        $class =  "success";  $status = 1; $status_name = "Read";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" visitor_message_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" visitor_message_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" visitor_message_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Create Visitor Message';
        return view('admin.website.parcelStep.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
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
            'name'         => $request->input('name'),
            'short_details'         => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = VisitorMessage::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Visitor Message Create Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('Visitor Message Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, VisitorMessage $visitorMessage) {
        return view('admin.website.visitorMessage.show', compact('visitorMessage'));
    }

    public function edit(Request $request, VisitorMessage $visitorMessage) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Edit Visitor Message';
        $data['parcelStep']      = $parcelStep;
        return view('admin.website.parcelStep.edit', $data);
    }

    public function update(Request $request, VisitorMessage $parcelStep) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
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
            'name'         => $request->input('name'),
            'short_details'       => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = VisitorMessage::where('id', $parcelStep->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Visitor Message Update Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('Visitor Message Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'visitor_message_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = VisitorMessage::where('id', $request->visitor_message_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => "Visitor Message Read Status Update Successfully",
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

    public function destroy(Request $request, VisitorMessage $visitorMessage) {

        $check = $visitorMessage->delete() ? true : false;
        if ($check) {
            $this->setMessage('Visitor Message Delete Successfully', 'success');
        } else {
            $this->setMessage('Visitor Message Delete Failed', 'danger');
        }
        return redirect()->route('admin.visitorMessage.index');
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
                'visitor_message_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = VisitorMessage::where('id', $request->visitor_message_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Visitor Message Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

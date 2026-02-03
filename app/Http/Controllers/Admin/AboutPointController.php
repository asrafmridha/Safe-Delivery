<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class AboutPointController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'aboutPoint';
        $data['page_title'] = 'About Points';
        return view('admin.website.aboutPoint.index', $data);
    }

    public function getAboutPoints(Request $request){
        $model  = AboutPoint::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/aboutPoint/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="About Point Image">';
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" about_point_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" about_point_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.aboutPoint.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" about_point_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'aboutPoint';
        $data['page_title'] = 'Create About Point';
        return view('admin.website.aboutPoint.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'details'  => 'required',
            'image'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/aboutPoint/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'title'         => $request->input('title'),
            'details'       => $request->input('details'),
            'image'         => $image_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = AboutPoint::create($data) ? true : false;

        if ($check) {
            $this->setMessage('About Point Create Successfully', 'success');
            return redirect()->route('admin.aboutPoint.index');
        } else {
            $this->setMessage('About Point Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, AboutPoint $aboutPoint) {
        return view('admin.website.aboutPoint.show', compact('aboutPoint'));
    }

    public function edit(Request $request, AboutPoint $aboutPoint) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'aboutPoint';
        $data['page_title'] = 'Edit About Point';
        $data['aboutPoint']      = $aboutPoint;
        return view('admin.website.aboutPoint.edit', $data);
    }

    public function update(Request $request, AboutPoint $aboutPoint) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'details'  => 'required',
            'image'    => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/aboutPoint/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($aboutPoint->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/aboutPoint/' . $aboutPoint->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $aboutPoint->image;
        }

        $data = [
            'title'         => $request->input('title'),
            'details'       => $request->input('details'),
            'image'         => $image_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = AboutPoint::where('id', $aboutPoint->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('About Point Update Successfully', 'success');
            return redirect()->route('admin.aboutPoint.index');
        } else {
            $this->setMessage('About Point Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'about_point_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = AboutPoint::where('id', $request->about_point_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'About Point Status Update Successfully',
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

    public function destroy(Request $request, AboutPoint $aboutPoint) {

        if (!empty($aboutPoint->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/aboutPoint/' . $aboutPoint->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $aboutPoint->delete() ? true : false;

        if ($check) {
            $this->setMessage('About Point Delete Successfully', 'success');
        } else {
            $this->setMessage('About Point Delete Failed', 'danger');
        }
        return redirect()->route('admin.aboutPoint.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'about_point_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $aboutPoint    = AboutPoint::where('id', $request->about_point_id)->first();
                $check      = AboutPoint::where('id', $request->about_point_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($aboutPoint->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/aboutPoint/' . $aboutPoint->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'About Point Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

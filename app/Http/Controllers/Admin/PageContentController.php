<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class PageContentController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'pageContent';
        $data['page_title'] = 'Page Contents';
        return view('admin.website.pageContent.index', $data);
    }

    public function getPageContents(Request $request){
        $model  = PageContent::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/pageContent/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Page Content Image">';
                    }
                    return $image;
                })
                ->editColumn('page_type', function($data){
                    switch($data->page_type){
                        case '1' : $page_type = "About Page"; break;
                        case '2' : $page_type = "Service Page"; break;
                        case '3' : $page_type = "Merchant Registration Page"; break;
                        case '4' : $page_type = "Privacy Policy Page"; break;
                        default : $page_type = "Other Page"; break;
                    }
                    return $page_type;
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" page_content_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" page_content_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.pageContent.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" page_content_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['page_type', 'short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'pageContent';
        $data['page_title'] = 'Create Page Content';
        return view('admin.website.pageContent.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'page_type'  => 'required|unique:page_contents',
            'short_details'  => 'sometimes',
            'long_details'  => 'sometimes',
            'image'    => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/pageContent/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'page_type'         => $request->input('page_type'),
            'short_details'     => $request->input('short_details'),
            'long_details'      => $request->input('long_details'),
            'image'             => $image_name,
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = PageContent::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Page Content Create Successfully', 'success');
            return redirect()->route('admin.pageContent.index');
        } else {
            $this->setMessage('Page Content Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, PageContent $pageContent) {
        return view('admin.website.pageContent.show', compact('pageContent'));
    }

    public function edit(Request $request, PageContent $pageContent) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'pageContent';
        $data['page_title'] = 'Edit Page Content';
        $data['pageContent']      = $pageContent;
        return view('admin.website.pageContent.edit', $data);
    }

    public function update(Request $request, PageContent $pageContent) {
        $validator = Validator::make($request->all(), [
            'page_type'     => 'required|unique:page_contents,page_type,'. $pageContent->id,
            'short_details'  => 'sometimes',
            'long_details'  => 'sometimes',
            'image'         => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/pageContent/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($pageContent->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/pageContent/' . $pageContent->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $pageContent->image;
        }

        $data = [
            'short_details'         => $request->input('short_details'),
            'long_details'          => $request->input('long_details'),
            'image'                 => $image_name,
            'updated_admin_id'      => auth()->guard('admin')->user()->id,
        ];

        $check = PageContent::where('id', $pageContent->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Page Content Update Successfully', 'success');
            return redirect()->route('admin.pageContent.index');
        } else {
            $this->setMessage('Page Content Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'page_content_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = PageContent::where('id', $request->page_content_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Page Content Status Update Successfully',
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

    public function destroy(Request $request, PageContent $pageContent) {

        if (!empty($pageContent->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/pageContent/' . $pageContent->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $pageContent->delete() ? true : false;

        if ($check) {
            $this->setMessage('Page Content Delete Successfully', 'success');
        } else {
            $this->setMessage('Page Content Delete Failed', 'danger');
        }
        return redirect()->route('admin.pageContent.index');
    }


    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'page_content_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $pageContent    = PageContent::where('id', $request->page_content_id)->first();
                $check      = PageContent::where('id', $request->page_content_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($pageContent->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/pageContent/' . $pageContent->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Page Content Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class BlogController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'blog';
        $data['page_title'] = 'Blogs';
        return view('admin.website.blog.index', $data);
    }

    public function getBlogs(Request $request){
        $model  = Blog::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/blog/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Blog Image">';
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
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" blog_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" blog_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.blog.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" blog_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['short_details','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'blog';
        $data['page_title'] = 'Create Blog';
        return view('admin.website.blog.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|unique:blogs',
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'required|image',
            'date'  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/blog/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $data = [
            'title'         => $request->input('title'),
            'slug'          => str_slug($request->input('title')),
            'short_details'         => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'date'       => $request->input('date'),
            'image'         => $image_name,
            'created_admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = Blog::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Blog Create Successfully', 'success');
            return redirect()->route('admin.blog.index');
        } else {
            $this->setMessage('Blog Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Blog $blog) {
        return view('admin.website.blog.show', compact('blog'));
    }

    public function edit(Request $request, Blog $blog) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'blog';
        $data['page_title'] = 'Edit Blog';
        $data['blog']      = $blog;
        return view('admin.website.blog.edit', $data);
    }

    public function update(Request $request, Blog $blog) {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|unique:blogs,title,' . $blog->id,
            'short_details'  => 'required',
            'long_details'  => 'required',
            'image'    => 'sometimes|image',
            'date'  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/blog/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($blog->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/blog/' . $blog->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        }
        else{
            $image_name = $blog->image;
        }

        $data = [
            'title'         => $request->input('title'),
            'slug'         => str_slug($request->input('title')),
            'short_details'       => $request->input('short_details'),
            'long_details'       => $request->input('long_details'),
            'image'         => $image_name,
            'date'       => $request->input('date'),
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = Blog::where('id', $blog->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Blog Update Successfully', 'success');
            return redirect()->route('admin.blog.index');
        } else {
            $this->setMessage('Blog Update Failed', 'danger');
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
                'blog_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Blog::where('id', $request->blog_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Blog Status Update Successfully',
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

    public function destroy(Request $request, Blog $blog) {

        if (!empty($blog->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/blog/' . $blog->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $blog->delete() ? true : false;

        if ($check) {
            $this->setMessage('Blog Delete Successfully', 'success');
        } else {
            $this->setMessage('Blog Delete Failed', 'danger');
        }
        return redirect()->route('admin.Blog.index');
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
                'blog_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $blog    = Blog::where('id', $request->blog_id)->first();
                $check      = Blog::where('id', $request->blog_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($blog->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/blog/' . $blog->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Blog Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsLetter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class NewsLetterController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'newsLetter';
        $data['page_title'] = 'News Letters';
        return view('admin.website.NewsLetter.index', $data);
    }

    public function getNewsLetters(Request $request){
        $model  = NewsLetter::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" news_letter_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'newsLetter';
        $data['page_title'] = 'Create News Letter';
        return view('admin.website.newsLetter.create', $data);
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
            'admin_id'    => auth()->guard('admin')->user()->id,
        ];
        $check = NewsLetter::create($data) ? true : false;

        if ($check) {
            $this->setMessage('News Letter Create Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('News Letter Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, NewsLetter $visitorMessage) {
        return view('admin.website.visitorMessage.show', compact('NewsLetter'));
    }

    public function edit(Request $request, NewsLetter $NewsLetter) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'parcelStep';
        $data['page_title'] = 'Edit News Letter';
        $data['parcelStep']      = $parcelStep;
        return view('admin.website.parcelStep.edit', $data);
    }

    public function update(Request $request, NewsLetter $NewsLetter) {
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
            $image_path = 'public/uploads/NewsLetter/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($NewsLetter->image)) {
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

        $check = NewsLetter::where('id', $NewsLetter->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('News Letter Update Successfully', 'success');
            return redirect()->route('admin.parcelStep.index');
        } else {
            $this->setMessage('News Letter Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'news_letter_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = VisitorMessage::where('id', $request->news_letter_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => "News Letter Read Status Update Successfully",
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
            $this->setMessage('News Letter Delete Successfully', 'success');
        } else {
            $this->setMessage('News Letter Delete Failed', 'danger');
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
                'news_letter_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = NewsLetter::where('id', $request->news_letter_id)->delete() ? true : false;
                if ($check) {
                    $response = ['success' => 'News Letter Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

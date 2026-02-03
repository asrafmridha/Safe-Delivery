<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ContentController extends Controller
{
    public function becomeMerchant() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'becomeMerchant';
        $data['page_title'] = 'Become Merchant';
        $data['content_data'] = Content::where('content_type', 'become_merchant')->first();
        return view('admin.website.content.become_merchant', $data);
    }

    public function becomeFranchisee() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'becomeFranchisee';
        $data['page_title'] = 'Become Franchisee';
        $data['content_data'] = Content::where('content_type', 'become_franchisee')->first();
        return view('admin.website.content.become_franchisee', $data);
    }


    public function storeAndUpdate(Request $request, $id='')
    {
        if($id != '') {

            $content_data   = Content::where('id', $id)->first();

            $validator = Validator::make($request->all(), [
                'title'         => 'required',
                'short_details' => 'required',
                'photo'         => 'sometimes|image',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            if ($request->hasFile('photo')) {
                $image      = $request->file('photo');
                $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
                $image_path = 'public/uploads/contents/' . $image_name;
                Image::make($image)->save($image_path);

                if (!empty($content_data->photo)) {
                    $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/contents/' . $content_data->photo;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            }
            else{
                $image_name = $content_data->photo;
            }

            $data = [
                'title'                 => $request->input('title'),
                'short_details'         => $request->input('short_details'),
                'photo'                 => $image_name,
                'updated_admin_id'      => auth()->guard('admin')->user()->id,
            ];

            $check = $content_data->update($data) ? true : false;

            if ($check) {
                $this->setMessage('Content Update Successfully', 'success');
                return redirect()->back();
            } else {
                $this->setMessage('Content Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        }else{
            $validator = Validator::make($request->all(), [
                'title'         => 'required',
                'short_details' => 'required',
                'photo'         => 'required|image',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            if ($request->hasFile('photo')) {
                $image      = $request->file('photo');
                $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
                $image_path = 'public/uploads/contents/' . $image_name;
                Image::make($image)->save($image_path);
            } else {
                $image_name = null;
            }


            $data = [
                'title'                 => $request->input('title'),
                'content_type'          => $request->input('content_type'),
                'short_details'         => $request->input('short_details'),
                'photo'                 => $image_name,
                'created_admin_id'    => auth()->guard('admin')->user()->id,
            ];
            $check = Content::create($data) ? true : false;

            if ($check) {
                $this->setMessage('Content Create Successfully', 'success');
                return redirect()->back();
            } else {
                $this->setMessage('Content Create Failed', 'danger');
                return redirect()->back()->withInput();
            }

        }
    }

}

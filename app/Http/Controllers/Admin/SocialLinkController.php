<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class SocialLinkController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'socialLink';
        $data['page_title'] = 'Social Links';
        return view('admin.website.socialLink.index', $data);
    }

    public function getSocialLinks(Request $request){
        $model  = SocialLink::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('icon', function($data){
                    return '<i class="'.$data->icon.' fa-lg" ></i>';
                })
                ->editColumn('url', function($data){
                    return '<a href="'.$data->url.'" target="_blank"> '.$data->url.'</a>';
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" social_link_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" social_link_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.socialLink.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" social_link_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['icon', 'url','status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'socialLink';
        $data['page_title'] = 'Create Social Link';
        return view('admin.website.socialLink.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'icon'      => 'required|min:3|unique:social_links',
            'url'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $icon = $request->input('icon');
        switch($icon){
            case "fab fa-facebook" : $name = "Facebook"; break;
            case "fab fa-twitter" : $name = "Twitter"; break;
            case "fab fa-instagram" : $name = "Instagram"; break;
            case "fab fa-youtube" : $name = "Youtube"; break;
            case "fab fa-linkedin" : $name = "Linkedin"; break;
            case "fab fa-skype" : $name = "Skype"; break;
            case "fab fa-google-plus" : $name = "Google+"; break;
            case "fab fa-whatsapp" : $name = "Whatsapp"; break;
            default : $name = ""; break;
        }

        $data = [
            'name'              => $name,
            'icon'              => $icon,
            'url'               => $request->input('url'),
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];

        $check = SocialLink::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Social Link Create Successfully', 'success');
            return redirect()->route('admin.socialLink.index');
        } else {
            $this->setMessage('Social Link Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, SocialLink $socialLink) {
        return view('admin.website.socialLink.show', compact('socialLink'));
    }

    public function edit(Request $request, SocialLink $socialLink) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'socialLink';
        $data['page_title'] = 'Edit Social Link';
        $data['socialLink']      = $socialLink;
        return view('admin.website.socialLink.edit', $data);
    }

    public function update(Request $request, SocialLink $socialLink) {
        $validator = Validator::make($request->all(), [
            'icon'          => 'required|min:3|unique:social_links,icon,'.$socialLink->id,
            'url'           => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $icon = $request->input('icon');
        switch($icon){
            case "fab fa-facebook" : $name = "Facebook"; break;
            case "fab fa-twitter" : $name = "Twitter"; break;
            case "fab fa-instagram" : $name = "Instagram"; break;
            case "fab fa-youtube" : $name = "Youtube"; break;
            case "fab fa-linkedin" : $name = "Linkedin"; break;
            case "fab fa-skype" : $name = "Skype"; break;
            case "fab fa-google-plus" : $name = "Google+"; break;
            case "fab fa-whatsapp" : $name = "Whatsapp"; break;
            default : $name = ""; break;
        }


        $data = [
            'name'             => $name,
            'icon'             => $icon,
            'url'               => $request->input('url'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = SocialLink::where('id', $socialLink->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Social Link Update Successfully', 'success');
            return redirect()->route('admin.socialLink.index');
        } else {
            $this->setMessage('Social Link Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'social_link_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = SocialLink::where('id', $request->social_link_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Social Link Status Update Successfully',
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

    public function destroy(Request $request, SocialLink $socialLink) {

        $check = $socialLink->delete() ? true : false;

        if ($check) {
            $this->setMessage('Social Link Delete Successfully', 'success');
        } else {
            $this->setMessage('Social Link Delete Failed', 'danger');
        }
        return redirect()->route('admin.socialLink.index');
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
                'social_link_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = SocialLink::where('id', $request->social_link_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Social Link Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

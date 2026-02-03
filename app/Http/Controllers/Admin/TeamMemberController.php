<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class TeamMemberController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'teamMember';
        $data['page_title'] = 'Team Members';
        return view('admin.website.teamMember.index', $data);
    }

    public function getTeamMembers (Request $request){
        $model  = TeamMember::with('designation')->select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/teamMember/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Team Member Image">';
                    }
                    return $image;
                })
                ->addColumn('designation_name', function($data){
                    return $data->designation->name;
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" team_member_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" team_member_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.teamMember.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" team_member_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['designation_name', 'status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'teamMember';
        $data['page_title'] = 'Create Partner';
        $data['designations'] = Designation::where('status' , 1)->get();
        return view('admin.website.teamMember.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'designation_id'=> 'required',
            'message'       => 'sometimes',
            'image'         => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/teamMember/' . $photo_name;
            Image::make($photo)->save($photo_path);
        } else {
            $photo_name = null;
        }

        $data = [
            'name'              => $request->input('name'),
            'designation_id'    => $request->input('designation_id'),
            'message'           => $request->input('message'),
            'image'             => $photo_name,
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = TeamMember::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Team Member Create Successfully', 'success');
            return redirect()->route('admin.teamMember.index');
        } else {
            $this->setMessage('Team Member Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, TeamMember $teamMember) {
        $teamMember->load('designation');
        return view('admin.website.teamMember.show', compact('teamMember'));
    }

    public function edit(Request $request, TeamMember $teamMember) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'teamMember';
        $data['page_title'] = 'Edit Partner';
        $data['teamMember'] = $teamMember;
        $data['designations'] = Designation::where('status' , 1)->get();
        return view('admin.website.teamMember.edit', $data);
    }

    public function update(Request $request, TeamMember $teamMember) {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'designation_id'=> 'required',
            'message'       => 'sometimes',
            'image'         => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/teamMember/' . $photo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($teamMember->image)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/teamMember/' . $teamMember->image;

                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        else{
            $photo_name = $teamMember->image;
        }

        $data = [
            'name'              => $request->input('name'),
            'designation_id'    => $request->input('designation_id'),
            'message'           => $request->input('message'),
            'image'             => $photo_name,
            'updated_admin_id'    => auth()->guard('admin')->user()->id,
        ];

        $check = TeamMember::where('id', $teamMember->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Team Member Update Successfully', 'success');
            return redirect()->route('admin.teamMember.index');
        } else {
            $this->setMessage('Team Member Update Failed', 'danger');
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
                'team_member_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = TeamMember::where('id', $request->team_member_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Team Member Status Update Successfully',
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

    public function destroy(Request $request, TeamMember $teamMember) {

        if (!empty($teamMember->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/teamMember/' . $teamMember->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $teamMember->delete() ? true : false;

        if ($check) {
            $this->setMessage('Team Member Delete Successfully', 'success');
        } else {
            $this->setMessage('Team  Member Delete Failed', 'danger');
        }
        return redirect()->route('admin.teamMember.index');
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
                'team_member_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $teamMember = TeamMember::where('id', $request->team_member_id)->first();
                $check = TeamMember::where('id', $request->team_member_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($teamMember->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/teamMember/' . $teamMember->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Team Member Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

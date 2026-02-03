<?php
namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DataTables;


class PartnerController extends Controller {
    public function index() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'partner';
        $data['page_title'] = 'Partners';
        return view('admin.website.partner.index', $data);
    }

    public function getPartners (Request $request){
        $model  = Partner::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $image = "";
                    if(!empty($data->image)){
                    $image = ' <img src="'. asset('uploads/partner/'.$data->image) .'"
                                class="img-fluid img-thumbnail"
                                style="height: 55px !important; width: 100px !important;" alt="Partner Image">';
                    }
                    return $image;
                })
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" partner_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->editColumn('url', function($data){
                    return '<a href="'.$data->url.'" target="_blank" >'.$data->url.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" partner_id="'.$data->id.'}" >
                                <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.partner.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';

                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" partner_id="'.$data->id.'">
                    <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['url','status', 'action', 'image'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'partner';
        $data['page_title'] = 'Create Partner';
        return view('admin.website.partner.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'url'           => 'sometimes',
            'image'         => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/partner/' . $photo_name;
            Image::make($photo)->save($photo_path);
        } else {
            $photo_name = null;
        }

        $data = [
            'name'              => $request->input('name'),
            'url'               => $request->input('url'),
            'image'             => $photo_name,
            'created_admin_id'  => auth()->guard('admin')->user()->id,
        ];
        $check = Partner::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Partner Create Successfully', 'success');
            return redirect()->route('admin.partner.index');
        } else {
            $this->setMessage('Partner Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Partner $partner) {
        return view('admin.website.partner.show', compact('partner'));
    }

    public function edit(Request $request, Partner $partner) {
        $data               = [];
        $data['main_menu']  = 'website';
        $data['child_menu'] = 'slider';
        $data['page_title'] = 'Edit Slider';
        $data['partner']      = $partner;
        $data['designations'] = Partner::where('status' , 1)->get();
        return view('admin.website.partner.edit', $data);
    }

    public function update(Request $request, Partner $partner) {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'url'           => 'sometimes',
            'image'         => 'sometimes|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $photo      = $request->file('image');
            $photo_name = time() . str_random() . rand(1, 10000) . '.' . $photo->getClientOriginalExtension();
            $photo_path = 'public/uploads/partner/' . $photo_name;
            Image::make($photo)->save($photo_path);

            if (!empty($partner->image)) {
                $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/partner/' . $partner->image;

                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
        }
        else{
            $photo_name = $partner->image;
        }

        $data = [
            'name'              => $request->input('name'),
            'url'               => $request->input('url'),
            'image'             => $photo_name,
            'updated_admin_id'  => auth()->guard('admin')->user()->id,
        ];

        $check = Partner::where('id', $partner->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Partner Update Successfully', 'success');
            return redirect()->route('admin.partner.index');
        } else {
            $this->setMessage('Partner Update Failed', 'danger');
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
                'partner_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Partner::where('id', $request->partner_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Partner Status Update Successfully',
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

    public function destroy(Request $request, Partner $partner) {

        if (!empty($partner->image)) {
            $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/partner/' . $partner->image;
            if (file_exists($old_photo_path)) {
                unlink($old_photo_path);
            }
        }

        $check = $partner->delete() ? true : false;

        if ($check) {
            $this->setMessage('Partner Delete Successfully', 'success');
        } else {
            $this->setMessage('Partner Delete Failed', 'danger');
        }
        return redirect()->route('admin.partner.index');
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
                'partner_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $partner = Partner::where('id', $request->partner_id)->first();
                $check = Partner::where('id', $request->partner_id)->delete() ? true : false;
                if ($check) {
                    if (!empty($partner->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/partner/' . $partner->image;
                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    $response = [ 'success' => 'Partner Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}

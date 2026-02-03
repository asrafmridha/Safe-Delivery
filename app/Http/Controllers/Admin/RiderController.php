<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Rider;
use App\Models\District;
use App\Models\Upazila;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class RiderController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'rider';
        $data['collapse'] = 'sidebar-collapse';
        $data['page_title'] = 'Riders';
        return view('admin.team.rider.index', $data);
    }

    public function getRiders(Request $request)
    {
        $model = Rider::with(['branch', 'district', 'upazila', 'area', 'branch'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";

                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/rider/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Rider Image">';
                }
                return $image;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    $class = "success";
                    $status = 0;
                    $status_name = "Active";
                } else {
                    $class = "danger";
                    $status = 1;
                    $status_name = "Inactive";
                }
                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" rider_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" rider_id="' . $data->id . '}" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                if (auth()->guard('admin')->user()->type == 1 || auth()->guard('admin')->user()->type == 3) {
                    $button .= '<a href="' . route('admin.rider.riderLogin', $data->id) . '" class="btn btn-success btn-sm" target="_blank"> <i class="fas fa-sign-in-alt"></i> </a>&nbsp;&nbsp;';
                    $button .= '<a href="' . route('admin.rider.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                    // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" rider_id="' . $data->id . '"> <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function printRiders(Request $request)
    {
        $riders = Rider::with(['branch', 'district', 'upazila', 'area', 'branch'])->orderBy('id','desc')->get();
        return view('admin.team.rider.print', compact('riders'));
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'rider';
        $data['page_title'] = 'Create Rider';
        $data['districts'] = District::where('status', 1)->get();
        $data['branches'] = Branch::where('status', 1)->get();
        return view('admin.team.rider.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:riders',
            'name' => 'required',
            'address' => 'required',
            'salary' => 'required',
            'district_id' => 'required',
            // 'upazila_id'     => 'required',
            'area_id' => 'sometimes',
            'branch_id' => 'required',
            'contact_number' => 'required',
            'password' => 'sometimes',
            'image' => 'sometimes|image',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/rider/' . $image_name;
            Image::make($image)->save($image_path);
        } else {
            $image_name = null;
        }

        $password = $request->input('password') ?? 12345;
        $data = [
            'r_id' => $this->returnUniqueRiderId(),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id' => 0,
            'area_id' => $request->input('area_id') ?? 0,
            'branch_id' => $request->input('branch_id'),
            'contact_number' => $request->input('contact_number'),
            'salary' => $request->input('salary'),
            'password' => bcrypt($password),
            'store_password' => $password,
            'image' => $image_name,
            'date' => date("Y-m-d"),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Rider::create($data) ? true : false;

        if ($check) {
            // $this->adminDashboardCounterEvent();
            $this->setMessage('Rider Create Successfully', 'success');
            return redirect()->route('admin.rider.index');
        } else {
            $this->setMessage('Rider Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, Rider $rider)
    {
        $rider->load(['district', 'upazila', 'area', 'branch']);
        return view('admin.team.rider.show', compact('rider'));
    }

    public function edit(Request $request, Rider $rider)
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'rider';
        $data['page_title'] = 'Edit Rider';
        $data['districts'] = District::where('status', 1)->get();
        $data['upazilas'] = Upazila::where('district_id', $rider->district_id)->get();
        $data['areas'] = Area::where('upazila_id', $rider->upazila_id)->get();
        $data['branches'] = Branch::where('status', 1)->get();
        $data['rider'] = $rider;
        return view('admin.team.rider.edit', $data);
    }

    public function update(Request $request, Rider $rider)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:riders,email,' . $rider->id,
            'name' => 'required',
            'address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'     => 'required',
            'area_id' => 'sometimes',
            'branch_id' => 'required',
            'salary' => 'required',
            'contact_number' => 'required',
            'password' => 'sometimes',
            'image' => 'sometimes|image',
            'status' => 'sometimes',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
            $image_path = 'public/uploads/rider/' . $image_name;
            Image::make($image)->save($image_path);

            if (!empty($rider->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/rider/' . $rider->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            $image_name = $rider->image;
        }

        $password = $request->input('password') ?? 12345;
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id' => 0,
            'area_id' => $request->input('area_id') ?? 0,
            'branch_id' => $request->input('branch_id'),
            'contact_number' => $request->input('contact_number'),
            'salary' => $request->input('salary'),
            'password' => bcrypt($password),
            'store_password' => $password,
            'image' => $image_name,
            'status' => $request->input('status'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Rider::where('id', $rider->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Rider Update Successfully', 'success');
            return redirect()->route('admin.rider.index');
        } else {
            $this->setMessage('Rider Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function updateStatus(Request $request)
    {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Rider::where('id', $request->rider_id)->update(['status' => $request->status]) ? true : false;
                if ($check) {
                    $response = [
                        'success' => 'Rider Status Update Successfully',
                        'status' => $request->status,
                    ];
                } else {
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }
        return response()->json($response);
    }

    public function destroy(Request $request, Rider $rider)
    {
        $check = $rider->delete() ? true : false;

        if ($check) {
            $this->setMessage('Rider Delete Successfully', 'success');
        } else {
            $this->setMessage('Rider Delete Failed', 'danger');
        }
        return redirect()->route('admin.rider.index');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $rider = Rider::where('id', $request->rider_id)->first();
                $check = Rider::where('id', $request->rider_id)->delete() ? true : false;

                if ($check) {

                    if (!empty($rider->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/rider/' . $rider->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }
                    }

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Rider Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }


    public function riderLogin(Rider $rider)
    {

        if ($rider) {
            auth()->guard('rider')->login($rider);

            $notification = new \App\Http\Controllers\AuthController;
            $notification->setApplicationInformationIntoSession();

            $this->setMessage('Rider Login Successfully', 'success');
            return redirect()->route('rider.home');
        }
    }

    public function riderResult(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $rider = Rider::with('district', 'upazila', 'area')->where('id', $request->rider_id)->first();
                if ($rider) {
                    $response = [
                        'success' => 1,
                        'rider' => $rider
                    ];
                } else {
                    $response = ['error' => 1];
                }
            }
        }
        return response()->json($response);
    }

    public function riderOption(Request $request)
    {

        $text = $request->text ?? "";
        if ($request->ajax()) {
            $option = '<option value="0" data-charge="0">Select ' . $text . 'Rider </option>';

            $riders = Rider::where([
                ['status', '=', 1],
                ['branch_id', '=', $request->branch_id]
            ])->get();
            if ($riders->count() > 0) {

                foreach ($riders as $rider) {
                    $option .= '<option  value="' . $rider->id . '" >
                        ' . $rider->name . '
                    </option>';
                }
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }


}

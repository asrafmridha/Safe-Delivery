<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Models\Area;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Branch;
use App\Models\BranchAreas;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;


class AreaController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Areas';
        return view('admin.applicationSetting.area.index', $data);
    }
    
    
    
    
    
    
        
    
    //new branch && Area Start
    public function addBranchArea() {
        $data                = [];
        $data['main_menu']   = 'applicationSetting';
        $data['child_menu']  = 'branchArea';
        $data['page_title']  = 'Create Service Area Setting';
        $data['branches']    = Branch::where('status', 1)->get();
        $data['areas']       = Area::where('status', 1)->get(['id', 'name']);
        $data['branchAreas'] = BranchAreas::get(['area_id']);
        return view('admin.applicationSetting.branchArea.create', $data);
    }

    public function getAllArea(Request $request) {
        
        $branch_id = $request->branch_id;
        $branch    = BranchAreas::all();
        // $areas     = Area::where('status', 1)->get();
         $areas     = Area::where('status', 1)
             ->orderBy('name')
            ->get()
            ->groupBy('district_id');
            
            //  dd($areas);
        return view('admin.applicationSetting.branchArea.area_select', compact('areas', 'branch_id'));

    }

    public function storeBranchArea(Request $request) {
        
       

        try {

            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|',
                'area_id'   => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $area_id = $request->input('area_id');

            foreach ($area_id as $value) {
                $BA = BranchAreas::where('area_id', $value)->first();

                if (!$BA) {
                    $data = [
                        'branch_id' => $request->input('branch_id'),
                        'area_id'   => $value,
                    ];
                    BranchAreas::create($data);
                }else{
                   $BA->update(['branch_id' => $request->input('branch_id')]); 
                }

            }

            $BranchAreas = BranchAreas::where('branch_id', $request->branch_id)->pluck('area_id')->toArray();

            foreach ($BranchAreas as $BranchArea) {

                if (!in_array($BranchArea, $area_id)) {
                    $branchArea = BranchAreas::where('branch_id', $request->branch_id)
                        ->where('area_id', $BranchArea)->first();

                    $branchArea->delete();
                }

            }

            return redirect()->route('admin.branch.area.add')->with('message','Data Successfully Added!');

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    //new branch && Area End



    public function getAreas(Request $request)
    {
        $model = Area::with('upazila', 'district')->get();
        
        
        $access_token = pathao_access_token();
        
        
        return DataTables::of($model)
            ->addIndexColumn()
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

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" area_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('admin.area.edit', $data->id) . '" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" area_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
        //     ->addColumn('pathao_city', function ($data) use ($access_token) {
                
        // // $access_token = pathao_access_token();
        
        //         $pathao_cities = get_pathao_cities($access_token);
        //         $pathao_city_name='';
        //         foreach ($pathao_cities as $pathao_city){
        //             if ($data->pathao_city_id==$pathao_city['city_id']){
        //                 $pathao_city_name = $pathao_city['city_name'];
        //             }
        //         }
        //         return $pathao_city_name;
        //     })
        //     ->addColumn('pathao_zone', function ($data) use ($access_token) {
                
        // // $access_token = pathao_access_token();
        
        //         $pathao_zone_name='';
        //         if ($data->pathao_zone_id){
        //             $pathao_zones = get_pathao_zones($data->pathao_city_id,$access_token);
        //             foreach ($pathao_zones as $pathao_zone){
        //                 if ($data->pathao_zone_id==$pathao_zone['zone_id']){
        //                     $pathao_zone_name=$pathao_zone['zone_name'];
        //                 }
        //             }
        //         }
        //         return $pathao_zone_name;
        //     })
        //     ->addColumn('pathao_area', function ($data) use ($access_token) {
                
        // // $access_token = pathao_access_token();
        
        //         $pathao_area_name='';
        //         if ($data->pathao_area_id){
        //             $pathao_areas = get_pathao_areas($data->pathao_zone_id,$access_token);
        //             foreach ($pathao_areas as $pathao_area){
        //                 if ($data->pathao_area_id==$pathao_area['area_id']){
        //                     $pathao_area_name=$pathao_area['area_name'];
        //                 }
        //             }
        //         }
        //         return $pathao_area_name;
        //     })
            // ->rawColumns(['status', 'action', 'pathao_city', 'pathao_zone', 'pathao_area'])
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Create Area';
        $data['districts'] = District::where('status', 1)->get();
        return view('admin.applicationSetting.area.create', $data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name'        => 'required|unique:areas,name,NULL,id,upazila_id,' . $request->input('upazila_id') . ',district_id,' . $request->input('district_id'),
            'name' => 'required|unique:areas,name,NULL,id,district_id,' . $request->input('district_id'),
            // 'upazila_id'  => 'required',
            'district_id' => 'required',
            'post_code' => 'sometimes',
        ], [
            'name.unique' => 'This Area, Upazila and District Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name' => $request->input('name'),
            'post_code' => $request->input('post_code'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'upazila_id' => 0,
            'district_id' => $request->input('district_id'),
            'created_admin_id' => auth()->guard('admin')->user()->id,
        ];
        $check = Area::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Area Create Successfully', 'success');
            return redirect()->route('admin.area.index');
        } else {
            $this->setMessage('Area Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Area $area)
    {
        return view('admin.applicationSetting.area.show', compact('area'));
    }

    public function edit(Request $request, Area $area)
    {
        $data = [];
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Edit Area';
        $data['districts'] = District::where('status', 1)->get();
        
        $access_token = pathao_access_token();

        $data['pathao_cities'] = get_pathao_cities($access_token);
        if ($area->pathao_city_id){
            $data['pathao_zones'] = get_pathao_zones($area->pathao_city_id,$access_token);
        }
        if ($area->pathao_zone_id){
            $data['pathao_areas'] = get_pathao_areas($area->pathao_zone_id,$access_token);
        }





        // $data['upazilas']   = Upazila::where('district_id', $area->district_id)->get();
        $data['area'] = $area;
        return view('admin.applicationSetting.area.edit', $data);
    }

    public function update(Request $request, Area $area)
    {

        $validator = Validator::make($request->all(), [
            // 'name'        => 'required|unique:areas,name,' . $area->id . ',id,upazila_id,' . $request->input('upazila_id') . ',district_id,' . $request->input('district_id'),
            'name' => 'required|unique:areas,name,' . $area->id . ',id,district_id,' . $request->input('district_id'),
            // 'upazila_id'  => 'required',
            'district_id' => 'required',
            'post_code' => 'sometimes',
        ], [
            'name.unique' => 'This Area, Upazila and District Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name' => $request->input('name'),
            'post_code' => $request->input('post_code'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'       => $request->input('upazila_id'),
            'pathao_city_id' => $request->input('city_id'),
            'pathao_zone_id' => $request->input('zone_id'),
            'pathao_area_id' => $request->input('area_id'),
            'upazila_id' => 0,
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];

        $check = Area::where('id', $area->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Area Update Successfully', 'success');
            return redirect()->route('admin.area.index');
        } else {
            $this->setMessage('Area Update Failed', 'danger');
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
                'area_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Area::where('id', $request->area_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Area Status Update Successfully',
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

    public function destroy(Request $request, Area $area)
    {
        $check = $area->delete() ? true : false;

        if ($check) {
            $this->setMessage('Area Delete Successfully', 'success');
        } else {
            $this->setMessage('Area Delete Failed', 'danger');
        }

        return redirect()->route('admin.area.index');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'area_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            } else {
                $check = Area::where('id', $request->area_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Area Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function areaOption(Request $request)
    {
        if ($request->ajax()) {
            $option = '<option value="0">Select Area</option>';
            $areas = Area::where('upazila_id', $request->upazila_id)->get();
            foreach ($areas as $area) {
                $option .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }

    public function districtWiseAreaOption(Request $request)
    {
        if ($request->ajax()) {
            $option = '<option value="0">Select Area</option>';
            $areas = Area::where('district_id', $request->district_id)->get();
            foreach ($areas as $area) {
                $option .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }


    public function excelImport()
    {
        $data = [];
        $data['main_menu'] = 'applicationSetting';
        $data['child_menu'] = 'area';
        $data['page_title'] = 'Create Area';
        return view('admin.applicationSetting.area.excel_import', $data);
    }


    public function excelImportStore(Request $request)
    {

        Area::truncate();

        $file = $request->file('file')->store('import');

        $import = new AreaImport;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        $this->setMessage('Area Create Successfully', 'success');
        return redirect()->route('admin.area.index');

    }

}

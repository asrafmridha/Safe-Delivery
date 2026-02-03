<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MerchantBulkImport;
use App\Mail\VerifyMerchantEmail;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\EmailVerification;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\ServiceArea;
use App\Models\ServiceType;
use App\Models\Upazila;
use App\Models\WeightPackage;
use App\Notifications\MerchantRegisterNotification;
use App\Traits\UploadTrait;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\MerchantServiceAreaCodCharge;

class MerchantController extends Controller
{

    use UploadTrait;

    public function index()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['collapse'] = 'sidebar-collapse';
        $data['page_title'] = 'Merchants';
        return view('admin.team.merchant.index', $data);
    }

    public function getMerchants(Request $request)
    {
        $model = Merchant::with(['district', 'upazila', 'area', 'branch'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";

                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/merchant/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Merchant Image">';
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

                if (auth()->guard('admin')->user()->type == 1) {
                    return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" merchant_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
                } else {
                    return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px; pointer-events: none" > ' . $status_name . '</a>';
                }

            })
            ->editColumn('cod_charge', function ($data) {
                $cod_charge = "0 %";

                if (!empty($data->cod_charge)) {
                    $cod_charge = $data->cod_charge . ' %';
                } elseif (is_null($data->cod_charge)) {
                    $cod_charge = "";
                }

                return $cod_charge;
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" merchant_id="' . $data->id . '" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';

                $button .= '<a href="' . route('admin.merchant.merchantLogin', $data->id) . '" class="btn btn-success btn-sm" target="_blank"> <i class="fas fa-sign-in-alt"></i> </a>&nbsp;&nbsp;';

                if (auth()->guard('admin')->user()->type == 1) {
                    $button .= '<a href="' . route('admin.merchant.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                    // $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" merchant_id="' . $data->id . '"><i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            
            ->editColumn('created_at', function ($data) {
                
                

                return $data->created_at->format("d-M-Y");
            })
            
            ->addColumn('branch_name', function ($data) {
                if($data->branch_id){
                    $text='<span>'.$data->branch->name.'</span>';
                }else{
                      $text='<span class="text-danger">'.$data->branch->name.'</span>';
                }
                

                return $text;
            })
            ->rawColumns(['image', 'status', 'action', 'image','created_at','branch_name'])
            ->make(true);
    }

    public function printMerchants(Request $request)
    {
        $merchants = Merchant::with(['district', 'upazila', 'area', 'branch'])->orderBy('id', 'desc')->get();
        return view('admin.team.merchant.print', compact('merchants'));
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['page_title'] = 'Create Merchant';
        $data['districts'] = District::where('status', 1)->get();
        $data['serviceAreas'] = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();
        $data['branches'] = Branch::where('status', 1)->get();
        return view('admin.team.merchant.create', $data);
    }

    public function merchantBulkImport()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['page_title'] = 'Merchant Bulk Import';

        return view('admin.team.merchant.merchant_bulk_import', $data);
    }

    public function merchantBulkImportStore(Request $request)
    {
        $file = $request->file('file')->store('import');
        DB::beginTransaction();
        try {

            $import = new MerchantBulkImport();
            $import->import($file);

            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }
            \DB::commit();
            $this->setMessage('Check And Confirm Import', 'success');
            return redirect()->route('admin.merchant.merchantBulkImportCheck');
        } catch (\Exception $e) {
            \DB::rollback();
            // dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function merchantBulkImportCheck()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['page_title'] = 'Merchant Bulk Import';

        $import_merchant = \session()->has('import_merchant') ? \session()->get('import_merchant') : [];
//dd($import_merchant);
        $data['areas'] = Area::all();
        $data['districts'] = District::all();
        $data['branches'] = Branch::all();
        $data['import_merchants'] = $import_merchant;
        if (count($import_merchant) > 0) {
            return view('admin.team.merchant.merchant_bulk_import_check', $data);
        }

        return redirect()->route('admin.merchant.merchantBulkImport');
    }

    public function merchantBulkImportEntry(Request $request)
    {
        try {
            $merchants = $request->input('merchant');

            foreach ($merchants as $merchant) {
                $password = $merchant['password'] ?? 12345;
                $data = [
                    'm_id' => $merchant['m_id'],
                    'name' => $merchant['name'],
                    'email' => $merchant['email'],
                    'password' => bcrypt($password),
                    'store_password' => $password,
                    'company_name' => $merchant['company_name'],
                    'address' => $merchant['address'],
                    'contact_number' => $merchant['contact_number'],
                    'branch_id' => $merchant['branch_id'],
                    'district_id' => $merchant['district_id'],
                    'area_id' => $merchant['area_id'] ?? 0,
                    'business_address' => $merchant['business_address'],
                    'fb_url' => $merchant['fb_url'],
                    'web_url' => $merchant['web_url'],

                    'date' => date('Y-m-d'),
                    'created_admin_id' => auth()->guard('admin')->user()->id,
                ];
                Merchant::create($data);
            }

            $this->setMessage('Merchant Create Successfully', 'success');
            return redirect()->route('admin.merchant.index');

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function merchantBulkImportReset() {
        \session()->forget('import_merchant');
        $this->setMessage('Import reset successful!', 'success');
        return redirect()->route('admin.merchant.merchantBulkImport');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:merchants',
            'image' => 'sometimes|image|max:3000',
            'branch_id' => 'required',
            'on_board_branch_id' => 'sometimes',
            // 'cod_charge'        => 'sometimes',
            'password' => 'sometimes',
            'address' => 'sometimes',
            'contact_number' => 'required',
            'district_id' => 'required',
            // 'upazila_id'        => 'required',
            'area_id' => 'sometimes',
            'business_address' => 'sometimes',
            'fb_url' => 'sometimes',
            'web_url' => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no' => 'sometimes',
            'bank_name' => 'sometimes',
            'bkash_number' => 'sometimes',
            'nagad_number' => 'sometimes',
            'rocket_name' => 'sometimes',
            'nid_no' => 'sometimes',
            'nid_card' => 'sometimes|image|max:3000',
            'trade_license' => 'sometimes|image|max:3000',
            'tin_certificate' => 'sometimes|image|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $image_name = null;
            $trade_license = null;
            $nid_card = null;
            $tin_certificate = null;

            if ($request->hasFile('image')) {
                $image_name = $this->uploadFile($request->file('image'), '/merchant/');
            }

            if ($request->hasFile('trade_license')) {
                $trade_license = $this->uploadFile($request->file('trade_license'), '/merchant/');
            }

            if ($request->hasFile('nid_card')) {
                $nid_card = $this->uploadFile($request->file('nid_card'), '/merchant/');
            }

            if ($request->hasFile('tin_certificate')) {
                $tin_certificate = $this->uploadFile($request->file('tin_certificate'), '/merchant/');
            }

            $password = $request->input('password') ?? 12345;
            
            // dd($request->all());
            
            $data = [
                'm_id' => $this->returnUniqueMerchantId(),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($password),
                'store_password' => $password,
                'company_name' => $request->input('company_name'),
                'address' => $request->input('address'),
                'contact_number' => $request->input('contact_number'),
                'branch_id' => $request->input('branch_id'),
                'on_board_branch_id' => $request->input('on_board_branch_id'),
                // 'cod_charge'        => $request->input('cod_charge'),
                'district_id' => $request->input('district_id'),
                // 'upazila_id'        => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'business_address' => $request->input('business_address'),
                'fb_url' => $request->input('fb_url'),
                'web_url' => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'bank_name' => $request->input('bank_name'),
                'bkash_number' => $request->input('bkash_number'),
                'nagad_number' => $request->input('nagad_number'),
                'rocket_name' => $request->input('rocket_name'),
                'nid_no' => $request->input('nid_no'),
                'image' => $image_name,
                'trade_license' => $trade_license,
                'nid_card' => $nid_card,
                'tin_certificate' => $tin_certificate,
                'date' => date('Y-m-d'),
                'status' => 0,
                'created_admin_id' => auth()->guard('admin')->user()->id,
            ];

            $merchant = Merchant::create($data);

            if ($merchant) {
                $charge = $request->input('charge');
                $return_charge = $request->input('return_charge');
                $cod_charge = $request->input('cod_charge');
                $service_area_id = $request->input('service_area_id');
                $count = count($service_area_id);

                $sync_charge_data = [];
                $sync_return_charge_data = [];
                $sync_cod_charge_data = [];

                for ($i = 0; $i < $count; $i++) {
                    if (!is_null($cod_charge[$i])) {
                        $sync_cod_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'cod_charge' => $cod_charge[$i],
                        ];
                    }

                    if (!is_null($charge[$i])) {
                        $sync_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'charge' => $charge[$i],
                        ];
                    }

                    if (!is_null($return_charge[$i])) {
                        $sync_return_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'return_charge' => $return_charge[$i],
                        ];
                    }
                }

                $merchant->service_area_charges()->sync($sync_charge_data);
                $merchant->service_area_return_charges()->sync($sync_return_charge_data);
                $merchant->service_area_cod_charges()->sync($sync_cod_charge_data);

                \DB::commit();

                // $admin_users = Admin::all();
                // foreach ($admin_users as $admin) {
                //     $admin->notify(new MerchantRegisterNotification($merchant));
                // }

                // $this->adminDashboardCounterEvent();

                $this->setMessage('Merchant Create Successfully', 'success');
                return redirect()->route('admin.merchant.index');

            } else {
                $this->setMessage('Merchant Create Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function show(Request $request, Merchant $merchant)
    {
        $merchant->load([
            'district',
            // 'upazila',
            'branch',
            'area',
            'service_area_charges',
            'service_area_return_charges',
            'service_area_cod_charges'
        ]);

        $serviceAreas = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();

        return view('admin.team.merchant.show', compact('merchant', 'serviceAreas'));
    }

    public function edit(Request $request, Merchant $merchant)
    {
        $merchant->load(['service_area_charges', 'service_area_return_charges', 'service_area_cod_charges']);
// dd($merchant);
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['page_title'] = 'Edit Merchant';
        $data['districts'] = District::where('status', 1)->get();
        // $data['upazilas']     = Upazila::where('district_id', $merchant->district_id)->get();
        $data['areas'] = Area::where('upazila_id', $merchant->upazila_id)->get();
        $data['serviceAreas'] = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();
        $data['branches'] = Branch::where('status', 1)->get();
        $data['merchant'] = $merchant;
        return view('admin.team.merchant.edit', $data);
    }

    public function update(Request $request, Merchant $merchant)
    {
        
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:merchants,email,' . $merchant->id,
            'image' => 'sometimes|image|max:3000',
            'branch_id' => 'required',
            
            'on_board_branch_id' => 'sometimes',
            // 'cod_charge'        => 'sometimes',
            'password' => 'sometimes',
            'confirm_password' => 'sometimes',
            'address' => 'sometimes',
            'contact_number' => 'required',
            'district_id' => 'required',
            // 'upazila_id'        => 'required',
            'area_id' => 'sometimes',
            'business_address' => 'sometimes',
            'fb_url' => 'sometimes',
            'web_url' => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no' => 'sometimes',
            'bank_route_no' => 'sometimes',
            'bank_branch_name' => 'sometimes',
            'bank_name' => 'sometimes',
            'bkash_number' => 'sometimes',
            'nagad_number' => 'sometimes',
            'rocket_name' => 'sometimes',
            'nid_no' => 'sometimes',
            'nid_card' => 'sometimes|image|max:3000',
            'trade_license' => 'sometimes|image|max:3000',
            'tin_certificate' => 'sometimes|image|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $image_name = $merchant->image;
            $trade_license = $merchant->trade_license;
            $nid_card = $merchant->nid_card;
            $tin_certificate = $merchant->tin_certificate;

            if ($request->hasFile('image')) {
                $image_name = $this->uploadFile($request->file('image'), '/merchant/');

                if (!empty($merchant->image)) {
                    $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->image;

                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }

                }

            }

            if ($request->hasFile('trade_license')) {
                $trade_license = $this->uploadFile($request->file('trade_license'), '/merchant/');

                if (!empty($merchant->trade_license)) {
                    $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->trade_license;

                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }

                }

            }

            if ($request->hasFile('nid_card')) {
                $nid_card = $this->uploadFile($request->file('nid_card'), '/merchant/');

                if (!empty($merchant->nid_card)) {
                    $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->nid_card;

                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }

                }

            }

            if ($request->hasFile('tin_certificate')) {
                $tin_certificate = $this->uploadFile($request->file('tin_certificate'), '/merchant/');

                if (!empty($merchant->tin_certificate)) {
                    $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->tin_certificate;

                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }

                }

            }

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'company_name' => $request->input('company_name'),
                'address' => $request->input('address'),
                'contact_number' => $request->input('contact_number'),
                'branch_id' => $request->input('branch_id'),
                
                'on_board_branch_id' => $request->input('on_board_branch_id'),
                // 'cod_charge'        => $request->input('cod_charge'),
                'district_id' => $request->input('district_id'),
                // 'upazila_id'        => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'business_address' => $request->input('business_address'),
                'fb_url' => $request->input('fb_url'),
                'web_url' => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'bank_route_no' => $request->input('bank_route_no'),
                'bank_branch_name' => $request->input('bank_branch_name'),
                'bank_name' => $request->input('bank_name'),
                'bkash_number' => $request->input('bkash_number'),
                'nagad_number' => $request->input('nagad_number'),
                'rocket_name' => $request->input('rocket_name'),
                'nid_no' => $request->input('nid_no'),
                'otp_token_status' => $request->input('otp_token_status'),
                'email_verified_at' => $request->input('email_verified_at'),
                'status' => $request->input('status'),
                'payment_recived_by' => $request->input('payment_recived_by'),
                'image' => $image_name,
                'trade_license' => $trade_license,
                'nid_card' => $nid_card,
                'tin_certificate' => $tin_certificate,
                'date' => date('Y-m-d'),
//                'status' => 1,
                'updated_admin_id' => auth()->guard('admin')->user()->id,
            ];

            $password = $request->input('password');

            if ($password) {
                $data['password'] = bcrypt($password);
                $data['store_password'] = $password;
            }

            $check = Merchant::where('id', $merchant->id)->update($data) ? true : false;

            if ($check) {
                $charge = $request->input('charge');
                $return_charge = $request->input('return_charge');
                $cod_charge = $request->input('cod_charge');
                $service_area_id = $request->input('service_area_id');
                $count = count($service_area_id);

                $sync_charge_data = [];
                $sync_return_charge_data = [];
                $sync_cod_charge_data = [];

                for ($i = 0; $i < $count; $i++) {


                    if (!is_null($cod_charge[$i])) {
                        $sync_cod_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'cod_charge' => $cod_charge[$i],
                        ];
                    }

                    if (!is_null($charge[$i])) {
                        $sync_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'charge' => $charge[$i],
                        ];
                    }

                    if (!is_null($return_charge[$i])) {
                        $sync_return_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'return_charge' => $return_charge[$i],
                        ];
                    }

                }


                $merchant->service_area_charges()->sync($sync_charge_data);
                $merchant->service_area_return_charges()->sync($sync_return_charge_data);
                $merchant->service_area_cod_charges()->sync($sync_cod_charge_data);

                \DB::commit();
                $this->setMessage('Merchant Update Successfully', 'success');
                return redirect()->route('admin.merchant.index');
            } else {
                $this->setMessage('Merchant Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
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
                'merchant_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $merchant = Merchant::where('id', $request->merchant_id)->first();
                if (($merchant->branch_id && $merchant->branch_id !=0) || $merchant->status){
                    $check = $merchant->update(['status' => $request->status]) ? true : false;
                    if ($check) {
                        $response = [
                            'success' => 'Merchant Status Update Successfully',
                            'status' => $request->status,
                        ];
                    } else {
                        $response = [
                            'error' => 'Database Error Found',
                        ];
                    }
                }else{
                    $response = [
                        'error' => 'Please Assign Branch First!',
                    ];
                }

            }

        }

        return response()->json($response);
    }

    public function destroy(Request $request, Merchant $merchant)
    {
        $check = $merchant->delete() ? true : false;

        if ($check) {
            $this->setMessage('Merchant Delete Successfully', 'success');
        } else {
            $this->setMessage('Merchant Delete Failed', 'danger');
        }

        return redirect()->route('admin.merchant.index');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found 1'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'merchant_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found 2'];
            } else {
                $merchant = Merchant::where('id', $request->merchant_id)->first();
                $check = Merchant::where('id', $request->merchant_id)->delete() ? true : false;

                if ($check) {
                    $merchant->service_area_charges()->detach();


                    // $merchant->email_verifications()->delete();
                    // $this->adminDashboardCounterEvent();

                    if (!empty($merchant->image)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->image;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }

                    }

                    if (!empty($merchant->trade_license)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->trade_license;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }

                    }

                    if (!empty($merchant->nid_card)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->nid_card;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }

                    }

                    if (!empty($merchant->tin_certificate)) {
                        $old_photo_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->tin_certificate;

                        if (file_exists($old_photo_path)) {
                            unlink($old_photo_path);
                        }

                    }

                    $response = ['success' => 'Merchant Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }


    public function merchantLogin(Merchant $merchant)
    {

        if ($merchant) {
            auth()->guard('merchant')->login($merchant);

            $notification = new \App\Http\Controllers\AuthController;
            $notification->setApplicationInformationIntoSession();

            $this->setMessage('Merchant Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }
    }


    public function serviceAreaCharge(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'merchant_id' => 'required',
                'district_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $district = District::where('id', $request->district_id)->first();
                $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                    'service_area_id' => $district->service_area_id,
                    'merchant_id' => $request->merchant_id,
                ])->first();

                $charge = 0;

                if ($merchantServiceAreaCharge) {
                    $charge = $merchantServiceAreaCharge->charge;
                }

                $response = [
                    'success' => 1,
                    'charge' => $charge,
                ];
            }

        }

        return response()->json($response);
    }


    public function returnMerchantUpazilaWeightPackageOptionAndCharge(Request $request)
    {
        $response = ['error' => 1];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'merchant_id' => 'required',
                'district_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 1];
            } else {
                $charge = 0;
                $return_charge = 0;
                $code_charge_percent = 0;
                // $upazilaOption       = '<option value="0">Select Thana/Upazila</option>';
                $areaOption = '<option value="0">Select Area</option>';
                $serviceTypeOption = '<option value="0" data-charge="0">Select Service Type</option>';
                $itemTypeOption = '<option value="0" data-charge="0">Select Item Type</option>';
                $weightPackageOption = '<option value="0" data-charge="0">Select Weight Package </option>';

                // $upazilas = Upazila::where('district_id', $request->district_id)->get();
                // foreach ($upazilas as $upazila) {
                //     $upazilaOption .= '<option value="' . $upazila->id . '">' . $upazila->name . '</option>';
                // }

                $areas = Area::where('district_id', $request->district_id)->get();
                foreach ($areas as $area) {
                    $areaOption .= '<option value="' . $area->id . '">' . $area->name . '</option>';
                }

                $district = District::with(['service_area:id,cod_charge,default_charge'])->where('id', $request->district_id)->first();

                $merchant = Merchant::where('id', $request->merchant_id)->select("id", "cod_charge",'district_id')->first();


                if (!empty($district)) {
                    $charge = $district->service_area->default_charge;
                    // $charge = null;
                    if (!$charge) {
                        $charge = 60;
                    }

                    $service_area_id = $district->service_area_id;
                    $weightPackages = WeightPackage::with([
                        'service_area' => function ($query) use ($service_area_id) {
                            $query->where('service_area_id', '=', $service_area_id);
                        },
                    ])
                        ->where(['status' => 1])
                        ->orderBy('weight_type', 'asc')
                        ->get();

                    foreach ($weightPackages as $weightPackage) {
                        $rate = $weightPackage->rate;

                        if (!empty($weightPackage->service_area)) {
                            $rate = $weightPackage->service_area->rate;
                        }

                        $weightPackageOption .= '<option  value="' . $weightPackage->id . '" data-charge="' . $rate . '"> ' . $weightPackage->name . ' </option>';
                    }

                    $serviceTypes = ServiceType::where('service_area_id', $district->service_area_id)->get();
                    foreach ($serviceTypes as $serviceType) {
                        $serviceTypeOption .= '<option value="' . $serviceType->id . '" data-charge="' . $serviceType->rate . '">' . $serviceType->title . ' - ' . $serviceType->rate . ' tk extra</option>';
                    }
                    $itemTypes = ItemType::where('service_area_id', $district->service_area_id)->get();
                    foreach ($itemTypes as $itemType) {
                        $itemTypeOption .= '<option value="' . $itemType->id . '" data-charge="' . $itemType->rate . '">' . $itemType->title . ' - ' . $itemType->rate . ' tk extra</option>';
                    }

                    $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                        'service_area_id' => $district->service_area_id,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                    if ($merchantServiceAreaCharge) {
                        $charge = $merchantServiceAreaCharge->charge;
                    }
                    
                    
                    
                    
                    
                                         //new update for same city
                                        //  dd($merchant->district_id);
                    if($merchant->district_id!=1){
                        if ($merchant->district_id == $request->input('district_id')){
                            $serviceArea = ServiceArea::where('id',1)->first();
                            $charge=$serviceArea->default_charge;
    
                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id' => 1,
                                'merchant_id' => $request->merchant_id,
                            ])->first();
                        }else{
                            $serviceArea = ServiceArea::where('id',3)->first();
                            $charge=$serviceArea->default_charge;
    
    //                        $charge = $district->service_area->default_charge;
    
                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id' => 3,
                                'merchant_id' => $request->merchant_id,
                            ])->first();
                        }
                        if ($merchantServiceAreaCharge) {
                            $charge = $merchantServiceAreaCharge->charge;
                        }

                    }                                                       
                    
                    

                    //Set Default Return Charge 1/2 of Delivery Charge
                    $return_charge = $charge / 2;
                    $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                        'service_area_id' => $district->service_area_id,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                    if ($merchantServiceAreaReturnCharge) {
                        $return_charge = $merchantServiceAreaReturnCharge->return_charge;
                    }

                    $code_charge_percent = $district->service_area->cod_charge;

                    if ($code_charge_percent != 0) {

                        $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                            'service_area_id' => $district->service_area_id,
                            'merchant_id' => $request->merchant_id,
                        ])->first();

                        if ($merchantServiceAreaCodCharge) {
                            $code_charge_percent = $merchantServiceAreaCodCharge->cod_charge;
                        }
                    }


                }


                $response = [
                    'success' => 1,
                    // 'upazilaOption'       => $upazilaOption,
                    'areaOption' => $areaOption,
                    'weightPackageOption' => $weightPackageOption,
                    'charge' => $charge,
                    'return_charge' => $return_charge,
                    'cod_charge' => $code_charge_percent,
                    'serviceTypeOption' => $serviceTypeOption,
                    'itemTypeOption' => $itemTypeOption,
                ];
            }

        }

        return response()->json($response);
    }

    public function confirmMerchantRegistration(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:merchants',
            'password' => 'required',
            'confirm_password' => 'required',
            'address' => 'sometimes',
            'contact_number' => 'required',
            'district_id' => 'sometimes',
            // 'upazila_id'        => 'sometimes',
            'area_id' => 'sometimes',
            'business_address' => 'sometimes',
            // 'fb_url' => 'required',
            'web_url' => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no' => 'sometimes',
            'bank_name' => 'sometimes',
            'bkash_number' => 'sometimes',
            'nagad_number' => 'sometimes',
            'rocket_name' => 'sometimes',
            'nid_no' => 'sometimes',
            'nid_card' => 'sometimes|image|max:3000',
            'trade_license' => 'sometimes|image|max:3000',
            'tin_certificate' => 'sometimes|image|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        \DB::beginTransaction();
        try {

            $trade_license = null;
            $nid_card = null;
            $tin_certificate = null;

            if ($request->hasFile('trade_license')) {
                $trade_license = $this->uploadFile($request->file('trade_license'), '/merchant/');
            }

            if ($request->hasFile('nid_card')) {
                $nid_card = $this->uploadFile($request->file('nid_card'), '/merchant/');
            }

            if ($request->hasFile('tin_certificate')) {
                $tin_certificate = $this->uploadFile($request->file('tin_certificate'), '/merchant/');
            }

            $company_name = $request->input('company_name');
            $contact_number = $request->input('contact_number');
            $password = $request->input('password') ?? 12345;
            $otp_token = random_int(100000, 999999);

            $data = [
                'm_id' => $this->returnUniqueMerchantId(),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($password),
                'store_password' => $password,
                'company_name' => $request->input('company_name'),
                'address' => $request->input('address'),
                'contact_number' => $request->input('contact_number'),
                // 'district_id' => $request->input('district_id'),
                'district_id' => '1',
                // 'upazila_id'        => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'business_address' => $request->input('business_address'),
                'fb_url' => $request->input('fb_url'),
                'web_url' => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'bank_name' => $request->input('bank_name'),
                'bkash_number' => $request->input('bkash_number'),
                'nagad_number' => $request->input('nagad_number'),
                'rocket_name' => $request->input('rocket_name'),
                'nid_no' => $request->input('nid_no'),
                'trade_license' => $trade_license,
                'nid_card' => $nid_card,
                'tin_certificate' => $tin_certificate,
                'date' => date('Y-m-d'),
                'otp_token' => $otp_token,
                'otp_token_created' => date("Y-m-d H:i:s"),
                'otp_token_status' => 0,
                'status' => 0,
            ];


            $data_verification = [
                'token' => $this->generateRandomString(70),
                'type' => 3
            ];

            $merchant = Merchant::create($data);

            $check = $merchant ? true : false;

            if ($check) {

                $email_verification = $merchant->email_verifications()->save(new EmailVerification($data_verification));

                \DB::commit();


                // $admin_users = Admin::all();
                // foreach ($admin_users as $admin) {
                //     $admin->notify(new MerchantRegisterNotification($merchant));
                // }

                // $this->adminDashboardCounterEvent();

                // $application = Application::first();
                // Mail::to($request->input('email'))->send(new VerifyMerchantEmail($merchant, $application));

                $message = "Dear {$company_name}, ";
                // $message .= "Your OTP is {$otp_token} From Parceldex Ltd. Please Confirm your account and keep it secret";
                $message .= "Please Use this OTP {$otp_token} to complete your Sign Up procedures and verify your account";

                $this->send_reg_sms($contact_number, $message);

                return response()->json([
                        'success' => 200,
                        'type' => 'success',
                        'title' => 'Thankyou',
                        'message' => "Your Registration successfully Done. Stay with us. Your account will be activate very soon"]
                    , 200);
            } else {
                return response()->json([
                    'success' => 401,
                    'type' => 'error',
                    'title' => "Oop's",
                    'message' => "Your Registration Failed",
                    'error' => "Unauthorized"
                ], 401);
            }

        } catch (\Exception $e) {

            dd($e->getMessage());
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

}

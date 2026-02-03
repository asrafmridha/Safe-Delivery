<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Notice;
use App\Models\Upazila;
use App\Models\WeightPackage;
use App\Models\ParcelPaymentRequest;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller {

    public function home() {
        $merchant = auth()->guard('merchant')->user();
        $merchant_id = $merchant->id;
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Home';

        $not_dataa = auth()->guard('merchant')->user()->notifications;
        $not_data = auth()->guard('merchant')->user()->unreadNotifications;

        //dd($not_dataa, $not_data);



        $counter_data   = parent::returnDashboardCounterForMerchant($merchant_id);

        $data['counter_data']   = $counter_data;
        
         $data['recent_order']               = Parcel::where('merchant_id', $merchant_id)
                                                ->orderBy('created_at', 'desc') 
                                                ->take(5)
                                                ->get();


//        $data['total_parcel']               = Parcel::where('merchant_id', $merchant_id)
//                                            ->count();
//
//        $data['total_cancel_parcel']    = Parcel::where('merchant_id', $merchant_id)
//                                            ->where('status', 3)
//                                            ->count();
//
//        $data['total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status != ? and status < ?', [3,11])
//                                                ->count();
//
//        $data['total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "")', [3,11,24])
//                                                ->count();
//
//        $data['total_delivery_parcel']  = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3,1,2,3,4])
//                                                ->count();
//
//        $data['total_delivery_complete_parcel']  = Parcel::where('merchant_id', $merchant_id)
//                                                    ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
//                                                    ->count();
//
//        $data['total_partial_delivery_complete']  = Parcel::where('merchant_id', $merchant_id)
//                                                    ->whereRaw('status >= ? and delivery_type in (?) and payment_type = ?', [25,2,5])
//                                                    ->count();
//
//        $data['total_pending_delivery']  = Parcel::where('merchant_id', $merchant_id)
//                                                    ->whereRaw('status > 11 and delivery_type in (?)', [3])
//                                                    ->count();
//
//        $data['total_return_parcel']    = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status >= ? and delivery_type in (?,?)', [25,2,4])
//                                                ->count();
//
//        $data['total_return_complete_parcel']    = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status = ? and delivery_type in (?,?)', [36,2,4])
//                                                ->count();
//
//        $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
//                                                ->sum('merchant_paid_amount');
//
////        $data['total_pending_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
////                                                    ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
////                                                    ->toSql();
////
////        dd($merchant_id, $data['total_pending_collect_amount'] );
//
//
//
//        $data['total_collect_amount']    = Parcel::where('merchant_id', $merchant_id)
//                                                ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,5])
//                                                ->sum('merchant_paid_amount');






 // Humayun NEW code 



        // $totalDeliveryComplete = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1)')->select('id')->count();

        // $totalDeliveryPartial = Parcel::whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type = 2')->select('id')->count();

        // $totalDeliveryCancel = Parcel::whereRaw('delivery_branch_id != "" and status = 36 and delivery_type = 4')->select('id')->count();

        // $totalParcelAllTime = Parcel::where('status', 25)->select('id')->count();






        // $data['totalDeliveryCompletePercent'] = (int) round((($totalDeliveryComplete * 100) / $totalParcelAllTime));
        // $data['totalDeliveryPartialPercent'] = (int) round((($totalDeliveryPartial * 100) / $totalParcelAllTime));
        // $data['totalDeliveryCancelPercent'] = (int) round((($totalDeliveryCancel * 100) / $totalParcelAllTime));

        // dd($data['completePercent']);



        $current_date = date('Y-m-d');
        $temp_date1 =  date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
        $temp_date2 =  date('Y-m-d', strtotime('-2 day', strtotime($current_date)));
        $temp_date3 =  date('Y-m-d', strtotime('-3 day', strtotime($current_date)));
        $temp_date4 =  date('Y-m-d', strtotime('-4 day', strtotime($current_date)));
        $temp_date5 =  date('Y-m-d', strtotime('-5 day', strtotime($current_date)));
        $temp_date6 =  date('Y-m-d', strtotime('-6 day', strtotime($current_date)));


       
        $data['today_total_pickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [date("Y-m-d")])
                                            ->count();
                                            
        $data['yesterdayPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date1])
                                            ->count();
                                            
         $data['towDaysAgoPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date2])
                                            ->count(); 
                                            
          $data['threeDaysAgoPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date3])
                                            ->count();   
                                            
         $data['fourDaysAgoPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date4])
                                            ->count();                                     
                                            
         $data['fiveDaysAgoPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date5])
                                            ->count();                                     
                                            
         $data['sixDaysAgoPickupcomplete']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [$temp_date6])
                                            ->count();                                     
                                            
                                            
           $data['today_total_delivery']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$current_date])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count(); 
           $data['yesterdayDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date1])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count(); 
                                                 
                                                 
           $data['twoDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date2])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count();
                                                 
         $data['threeDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date3])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count();                                         
                                                 
         $data['fourDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date4])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count(); 
                                                 
         $data['fiveDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date5])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count();                                         
                                                 
         $data['sixDeliveryComplete']  = Parcel::where('merchant_id', $merchant_id)
                                                ->whereRaw('delivery_rider_date = ? ', [$temp_date6])
                                                ->whereRaw('status >= ? and delivery_type in (?)', [25,1])
                                                 ->count();                                         
                                                 
                                                 
                                                 



        
             $data['total_deleted_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereBetween('status', [2, 3])
                                              // ->whereRaw('(status >3 and status <25) or (status = 1)')
                                               ->count();

 // Humayun NEW code  end













             $total_customer_collect_amount      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1,2,2,4,6,0])
            ->sum('customer_collect_amount');
            

            $data['total_customer_collected_amount']      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?)', [1,2])
            ->sum('customer_collect_amount');
            

            $data['total_charge']      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            // ->whereRaw('delivery_type in (?,?,?)', [1,2,4])
            
            ->whereRaw('delivery_type in (?,?)', [1,2])
            ->sum('total_charge');
            
            $data['total_customer_collected_amount'] -= $data['total_charge'];
            
            
              $data['total_cancel_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [25,4])
                                               ->count();
                                               
              $data['total_return_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [36,4])
                                               ->count();                                 
                                               
              $data['today_cancel_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [25,4])
                                               ->whereRaw('delivery_branch_date = ? ', [date("Y-m-d")])
                                               ->count();  
                                               
             $data['today_partial_delivered']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [25,2])
                                               ->whereRaw('delivery_branch_date = ? ', [date("Y-m-d")])
                                               ->count();  
                                               
            $data['today_return']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [36,4])
                                               ->whereRaw('return_branch_date = ? ', [date("Y-m-d")])
                                               ->count(); 
                                               
             $data['today_return_process']    = Parcel::where('merchant_id', $merchant_id)
                                               ->whereRaw('status >= ? and delivery_type in (?)', [26,4])
                                               ->whereRaw('return_branch_date = ? ', [date("Y-m-d")])
                                               ->count();                                  
             
              $data['total_pending_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                                
                                               ->where(function ($query) {
                                                $query->whereBetween('status', [10, 24])
                                               ->whereNotIn('status', [21, 22])
                                               ->orWhere('delivery_type', 3);
                                               
                                                })
                                               
                                              // ->whereRaw('(status >3 and status <25) or (status = 1)')
                                               ->count();
                                               
                                               
             $data['merchant_cancel_parcel']    = Parcel::where('merchant_id', $merchant_id)
                                                ->where('status', 3)
                                                ->count();
                                                
            $data['total_pickup_pending']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('status >= ? AND status <= ? AND status != ?', [1, 9, 3])
                                            ->count();
                                              
             $data['today_total_pickup']    = Parcel::where('merchant_id', $merchant_id)
                                            ->whereRaw('pickup_branch_date = ? ', [date("Y-m-d")])
                                            ->count();
                                               

            $total_charge_amount                = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?,?) and payment_type in (?,?) and payment_request_status = ?', [1,2,4,2,6,0])
            ->sum('total_charge');

        $payment_request_data                = ParcelPaymentRequest::whereRaw("merchant_id = '{$merchant_id}' AND status < 5 AND status NOT IN (3)")->get();

        $data['total_pending_payment'] = number_format($total_customer_collect_amount - $total_charge_amount, 2, '.', '');

        $data['news']   = Notice::whereRaw('type = 2 and publish_for IN (0,2)')->orderBy('id', 'DESC')->first();
        $data['parcel_invoice']   = null;
        
//dd($data);
        return view('merchant.home', $data);
    }

    public function orderTracking($parcel_invoice = '') {

        $data               = [];
        $data['main_menu']  = 'orderTracking';
        $data['child_menu'] = 'orderTracking';
        $data['parcel_invoice'] = urldecode($parcel_invoice);
        $data['page_title'] = 'Order Tracking';
        return view('merchant.orderTracking', $data);
    }

    public function returnOrderTrackingResult(Request $request) {
        $parcel_invoice     = $request->input('parcel_invoice');
        $merchant_order_id  = $request->input('merchant_order_id');

        if((!is_null($parcel_invoice) && $parcel_invoice != '') || (!is_null($merchant_order_id) && $merchant_order_id != '')){
            $parcel = Parcel::with('district', 'upazila', 'area', 'merchant',
                    'weight_package', 'pickup_branch', 'pickup_rider',
                    'delivery_branch', 'delivery_rider')
                    ->where('merchant_id', auth()->guard('merchant')->user()->id )
                    ->where(function($query) use ($parcel_invoice, $merchant_order_id){
                        if(!is_null($parcel_invoice)){
                            $query->where('parcel_invoice','like', "$parcel_invoice");
                            $query->orWhere('merchant_order_id','like', "$parcel_invoice");
                            $query->orWhere('customer_contact_number','like', "$parcel_invoice");
                        }
                        elseif(!is_null($merchant_order_id)){
                           $query->where('merchant_order_id','like', "%$merchant_order_id");
                           $query->orWhere('customer_contact_number','like', "{$merchant_order_id}");
                        }
                    })
                    ->first();
            if($parcel){
                $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch',
                    'delivery_rider', 'admin', 'merchant')
                    ->where('parcel_id', $parcel->id)
                    ->orderBy('id', 'desc')
                    ->get();
                // dd($parcelLogs);
                return view('merchant.orderTrackingResult', compact('parcel', 'parcelLogs'));
            }
        }

    }

    public function profile() {
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Profile';
        $data['merchant']   = Merchant::with(['branch', 'district', 'upazila', 'area', 'service_area_charges'])->where('id', auth()->guard('merchant')->user()->id)->first();
        return view('merchant.profile', $data);
    }


    public function updateProfile(){
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Update Profile';
        $data['merchant']   = Merchant::with(['branch', 'district', 'upazila', 'area', 'service_area_charges'])->where('id', auth()->guard('merchant')->user()->id)->first();

        $data['districts']    = District::where('status', 1)->get();
        $data['upazilas']     = Upazila::where('district_id', $data['merchant']->district_id)->get();
        $data['areas']        = Area::where('upazila_id', $data['merchant']->upazila_id)->get();
        return view('merchant.updateProfile', $data);
    }



    public function confirmUpdateProfile(Request $request){

        $merchant = Merchant::find(auth()->guard('merchant')->user()->id);
        


        $validator = Validator::make($request->all(), [
            'company_name'      => 'required',
            'name'              => 'required',
            'email'             => 'required|email|unique:merchants,email,' . $merchant->id,
            'image'             => 'sometimes|image|max:3000',
            'password'          => 'sometimes|nullable|min:5',
            'address'           => 'sometimes',
            'contact_number'    => 'required',
            'district_id'       => 'required',
//            'upazila_id'        => 'required',
            'area_id'           => 'required',
            'business_address'  => 'sometimes',
            'fb_url'            => 'sometimes',
            'web_url'           => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no'   => 'sometimes',
            'bank_route_no'     => 'sometimes',
            'bank_branch_name'  => 'sometimes',
            'bank_name'         => 'sometimes',
            'bkash_number'      => 'sometimes',
            'nagad_number'      => 'sometimes',
            'rocket_name'       => 'sometimes',
            'nid_no'            => 'sometimes',
            'nid_card'          => 'sometimes|image|max:3000',
            'trade_license'     => 'sometimes|image|max:3000',
            'tin_certificate'   => 'sometimes|image|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $image_name      = $merchant->image;
            $trade_license   = $merchant->trade_license;
            $nid_card        = $merchant->nid_card;
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
// dd($image_name);
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
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'company_name'      => $request->input('company_name'),
                'address'           => $request->input('address'),
                'contact_number'    => $request->input('contact_number'),
                'district_id'       => $request->input('district_id'),
//                'upazila_id'        => $request->input('upazila_id'),
                'area_id'           => $request->input('area_id'),
                'business_address'  => $request->input('business_address'),
                'fb_url'            => $request->input('fb_url'),
                'web_url'           => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no'   => $request->input('bank_account_no'),
                'bank_route_no'     => $request->input('bank_route_no'),
                'bank_branch_name'  => $request->input('bank_branch_name'),
                'bank_name'         => $request->input('bank_name'),
                'bkash_number'      => $request->input('bkash_number'),
                'nagad_number'      => $request->input('nagad_number'),
                'rocket_name'       => $request->input('rocket_name'),
                'nid_no'            => $request->input('nid_no'),
                'image'             => $image_name,
                'trade_license'     => $trade_license,
                'nid_card'          => $nid_card,
                'tin_certificate'   => $tin_certificate,
                'payment_recived_by' => $request->input('payment_recived_by'),
                'date'              => date('Y-m-d'),
                'status'            => 1,
            ];
            

        // dd($data);

            $password = $request->input('password');

            if ($password) {
                $data['password']       = bcrypt($password);
                $data['store_password'] = $password;
            }

            $check = Merchant::where('id', $merchant->id)->update($data) ? true : false;

            if ($check) {


                \DB::commit();
                $this->setMessage('Merchant Update Profile Successfully', 'success');
                return redirect()->route('merchant.profile');
            } else {
                $this->setMessage('Merchant Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \DB::rollback();
            
        // dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            // $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function coverageArea() {
        $data               = [];
        $data['main_menu']  = 'coverageArea';
        $data['child_menu'] = 'coverageArea';
        $data['page_title'] = 'Coverage Area';
        return view('merchant.coverageArea', $data);
    }

    public function getCoverageAreas(Request $request) {
        $model = Area::with('district')->where('status', 1)->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    }

    public function serviceCharge() {
        $data               = [];
        $data['main_menu']  = 'serviceCharge';
        $data['child_menu'] = 'serviceCharge';
        $data['page_title'] = 'Service Charge ';
        return view('merchant.serviceCharge', $data);
    }

    public function getServiceCharges(Request $request) {
        $model = WeightPackage::where('status', 1)->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('rate', function ($data) {
                return number_format($data->rate, 2);
            })
            ->editColumn('weight_type', function ($data) {

                if ($data->weight_type == 1) {
                    $weight_type = "KG";
                } else {
                    $weight_type = "CFT";
                }

                return $weight_type;
            })
            ->rawColumns(['weight_type', 'rate'])
            ->make(true);
    }

}

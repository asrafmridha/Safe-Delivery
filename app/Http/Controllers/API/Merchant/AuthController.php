<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMerchantEmail;
use App\Models\Admin;
use App\Models\Application;
use App\Models\EmailVerification;
use App\Models\Merchant;
use App\Notifications\MerchantRegisterNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Traits\UploadTrait;

use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    use UploadTrait;

    /**
     * Get a JWT via given credentials.
     *
     * @param  Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $Validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required|min:5',
            ],
            [
                'email.required'    => 'Email is Required',
                'password.required' => 'Password is Required',
                'password.min'      => 'Password is Minimum 5',
            ]
        );

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }

        $user_name = "email";
        if(is_numeric($request->input('email'))){
            $user_name = "contact_number";
        }


//Code By Humayun
        if ($token = auth()->guard('merchant_api')->claims(['name' => 'beaconcourier'])->attempt([
            $user_name => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 0
        ])) {
            return response()->json([
                'success' => 401,
               // 'message' => "Userd ID Inactive Pending For approval From Admin",
                'message' => "Your account is currently pending approval by the administrator.",
                
                'error'   => "Unauthorized",
            ], 403);
        }
        
//End Code By Humayun         
        
        if ($token = auth()->guard('merchant_api')->claims(['name' => 'beaconcourier'])->attempt([
                $user_name => $request->input('email'),
                'password' => $request->input('password'),
                'status' => 1
            ])) {
            $merchant =  auth()->guard('merchant_api')->user();

            if($merchant->image){
                $merchant->image = asset('uploads/merchant/'.$merchant->image);
            }
            else{
                $merchant->image = asset('image/defaultMerchant.png');
            }

            if($merchant->trade_license){
                $merchant->trade_license = asset('uploads/merchant/'.$merchant->trade_license);
            }
            if($merchant->nid_card){
                $merchant->nid_card = asset('uploads/merchant/'.$merchant->nid_card);
            }
            if($merchant->tin_certificate){
                $merchant->tin_certificate = asset('uploads/merchant/'.$merchant->tin_certificate);
            }

            $service_area_charges =  auth()->guard('merchant_api')->user()->service_area_charges;

            $new_service_area = [];

            foreach($service_area_charges as $service_area_charge){
                $new_service_area[] = [
                    'name'          => $service_area_charge->name,
                    'cod_charge'    => $service_area_charge->cod_charge,
                    'cod_charge'    => $service_area_charge->cod_charge,
                    'weight_type'   => $service_area_charge->weight_type,
                    'charge'        => $service_area_charge->pivot->charge,
                ];
            }

            unset(
                $merchant->service_area_charges,
                $merchant->store_password,
                $merchant->created_admin_id,
                $merchant->updated_admin_id,
                $merchant->created_at,
                $merchant->updated_at
            );

            $cod_charge = 0;
            if($merchant->cod_charge){
                $cod_charge = $merchant->cod_charge;
            }

            return response()->json([
                'success'  => 200,
                'message'  => "Merchant Login Successfully",
                'token'    => $token,
                'merchant' => $merchant,
                'cod_charge_percent' => $cod_charge,
                'service_area_charges' => $new_service_area,

            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Merchant user Credential not Match",
            'error'   => "Unauthorized",
        ], 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('merchant_api')->logout();

        return response()->json([
            'success' => 200,
            'message' => "Merchant Successfully logged out",
        ], 200);

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param  Illuminate\Http\Request
     *
     * @return Illuminate\Http\Request;
     */
    public function registration(Request $request) {

        $Validator = Validator::make($request->all(), [
            'company_name'      => 'required',
            'name'              => 'required',
            'email'             => 'required|email|unique:merchants',
            'image'             => 'sometimes|max:2048',
            'password'          => 'sometimes',
            'address'           => 'sometimes',
            'contact_number'    => 'required',
            'district_id'       => 'sometimes',
            // 'upazila_id'        => 'sometimes',
            'area_id'           => 'sometimes',
            'business_address'  => 'sometimes',
            'fb_url'            => 'sometimes',
            'web_url'           => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no'   => 'sometimes',
            'bank_name'         => 'sometimes',
            'bkash_number'      => 'sometimes',
            'nagad_number'      => 'sometimes',
            'rocket_name'       => 'sometimes',
            'nid_no'            => 'sometimes',
            'nid_card'          => 'sometimes|max:3000',
            'trade_license'     => 'sometimes|max:3000',
            'tin_certificate'   => 'sometimes|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {

            $image_name      = null;
            $trade_license   = null;
            $nid_card        = null;
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


            $password       = $request->input('password') ?? 12345;
            $contact_number = $request->input('contact_number');
            $company_name   = $request->input('company_name');

            $otp_token      = random_int(100000,999999);


            $data     = [
                'm_id'              => $this->returnUniqueMerchantId(),
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'password'          => bcrypt($password),
                'store_password'    => $password,
                'company_name'      => $company_name,
                'address'           => $request->input('address'),
                'contact_number'    => $contact_number,
                'cod_charge'        => $request->input('cod_charge'),
                'district_id'       => $request->input('district_id') ?? 0,
                'upazila_id'        => 0,
                'area_id'           => $request->input('area_id') ?? 0,
                'business_address'  => $request->input('business_address'),
                'fb_url'            => $request->input('fb_url'),
                'web_url'           => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no'   => $request->input('bank_account_no'),
                'bank_name'         => $request->input('bank_name'),
                'bkash_number'      => $request->input('bkash_number'),
                'nagad_number'      => $request->input('nagad_number'),
                'rocket_name'       => $request->input('rocket_name'),
                'nid_no'            => $request->input('nid_no'),
                'image'             => $image_name,
                'trade_license'     => $trade_license,
                'nid_card'          => $nid_card,
                'tin_certificate'   => $tin_certificate,
                'date'              => date('Y-m-d'),
                'otp_token'         => $otp_token,
                'otp_token_created' => date("Y-m-d H:i:s"),
                'otp_token_status'  => 0,
                'status'            => 0,
            ];

            $data_verification  = [
                'token'         => $this->generateRandomString(70),
                'type'          => 3
            ];

            $merchant = Merchant::create($data);
            $check = $merchant ? true : false;

            if ($check) {

                $email_verification = $merchant->email_verifications()->save(new EmailVerification($data_verification));
                \DB::commit();

                /** For Notification and Counter */
                $admin_users = Admin::all();
                foreach ($admin_users as $admin) {
//                    $admin->notify(new MerchantRegisterNotification($merchant));
                }
                $this->adminDashboardCounterEvent();
                /** End For Notification and Counter */

                $application = Application::first();
//                Mail::to($request->input('email'))->send(new VerifyMerchantEmail($merchant, $application));


                $message    = "Dear {$company_name}, ";
                $message    .= "Your OTP is {$otp_token} From Delivery Now. Please Confirm your account and keep it secret.";

                $this->send_sms($contact_number, $message);

                return response()->json([
                    'success'  => 200,
                    'message'  => "Merchant OTP Has been send Successfully",
                ], 200);


            }
        } catch (\Exception$e) {
            return response()->json([
                'success' => 401,
                'message' => "Merchant Registration failed",
//                'error'   => "Unauthorized",
                'error'   => $e->getMessage(),
            ], 401);
        }
    }


    public function webRegistration(Request $request) {

        $Validator = Validator::make($request->all(), [
            'company_name'      => 'required',
            'name'              => 'required',
            'email'             => 'required|email|unique:merchants',
            'image'             => 'sometimes|max:2048',
            'password'          => 'sometimes',
            'address'           => 'sometimes',
            'contact_number'    => 'required',
            'district_id'       => 'required',
            'upazila_id'        => 'required',
            'area_id'           => 'required',
            'business_address'  => 'sometimes',
            'fb_url'            => 'sometimes',
            'web_url'           => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no'   => 'sometimes',
            'bank_name'         => 'sometimes',
            'bkash_number'      => 'sometimes',
            'nagad_number'      => 'sometimes',
            'rocket_name'       => 'sometimes',
            'nid_no'            => 'sometimes',
            'nid_card'          => 'sometimes|max:3000',
            'trade_license'     => 'sometimes|max:3000',
            'tin_certificate'   => 'sometimes|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {

            $image_name      = null;
            $trade_license   = null;
            $nid_card        = null;
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


            $password       = $request->input('password') ?? 12345;
            $contact_number = $request->input('contact_number');
            $company_name   = $request->input('company_name');

            $otp_token      = random_int(100000,999999);


            $data     = [
                'm_id'              => $this->returnUniqueMerchantId(),
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'password'          => bcrypt($password),
                'store_password'    => $password,
                'company_name'      => $company_name,
                'address'           => $request->input('address'),
                'contact_number'    => $contact_number,
                'cod_charge'        => $request->input('cod_charge'),
                'district_id'       => $request->input('district_id'),
                'upazila_id'        => $request->input('upazila_id'),
                'area_id'           => $request->input('area_id'),
                'business_address'  => $request->input('business_address'),
                'fb_url'            => $request->input('fb_url'),
                'web_url'           => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no'   => $request->input('bank_account_no'),
                'bank_name'         => $request->input('bank_name'),
                'bkash_number'      => $request->input('bkash_number'),
                'nagad_number'      => $request->input('nagad_number'),
                'rocket_name'       => $request->input('rocket_name'),
                'nid_no'            => $request->input('nid_no'),
                'image'             => $image_name,
                'trade_license'     => $trade_license,
                'nid_card'          => $nid_card,
                'tin_certificate'   => $tin_certificate,
                'date'              => date('Y-m-d'),
                'otp_token'         => $otp_token,
                'otp_token_created' => date("Y-m-d H:i:s"),
                'otp_token_status'  => 0,
                'status'            => 0,
            ];

            $data_verification  = [
                'token'         => $this->generateRandomString(70),
                'type'          => 3
            ];

            $merchant = Merchant::create($data);
            $check = $merchant ? true : false;

            if ($check) {

                $email_verification = $merchant->email_verifications()->save(new EmailVerification($data_verification));
                \DB::commit();

                /** For Notification and Counter */
                $admin_users = Admin::all();
                foreach ($admin_users as $admin) {
                    $admin->notify(new MerchantRegisterNotification($merchant));
                }
                $this->adminDashboardCounterEvent();
                /** End For Notification and Counter */

                $application = Application::first();
                Mail::to($request->input('email'))->send(new VerifyMerchantEmail($merchant, $application));


                $message    = "Dear {$company_name}, ";
                $message    .= "Your OTP is {$otp_token}. Please Confirm your account.";

                $this->send_sms($contact_number, $message);

//                return response()->json([
//                    'success'  => 200,
//                    'message'  => "Merchant OTP Has been send Successfully",
//                ], 200);

                return response()->json([
                        'success'   => 200,
                        'type'      => 'success',
                        'title'     => 'Thankyou',
                        'message'   => "Your Registration successfully Done. Stay with us. Your account will be activate very soon"]
                , 200);


            }
        } catch (\Exception$e) {

            return response()->json([
                'success'   => 401,
                'type'      => 'error',
                'title'     => "Oop's",
                'message'   => "Your Registration Failed",
                'error'     => "Unauthorized",
            ]);

//            return response()->json([
//                'success' => 401,
//                'message' => "Merchant Registration failed",
//                'error'   => "Unauthorized",
//            ], 401);
        }
    }


    public function confirmContactNumber(Request $request) {
        $Validator = Validator::make($request->all(), [
            'contact_number'    => 'required|numeric|digits:11',
            'otp_token'         => 'required|numeric|digits:6',
            ],
            [
                'contact_number.required'   => 'Password is Required',
                'contact_number.min'        => 'Password is minimum 11 Digit',
                'otp_token.required'        => 'OTP Token is Required',
                'otp_token.min'             => 'OTP Token is minimum 11 Digit',
            ]
        );

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }

        $contact_number = '0' . substr(preg_replace('/\D/', '', $request->input('contact_number')), -10);

        $merchant = Merchant::where([
            "contact_number"    => $contact_number,
            "otp_token"         =>  $request->input('otp_token')
            ])->first();

        if($merchant){

            Merchant::where('id', $merchant->id)->update([
                'status'            => 1,
                'otp_token'         => null,
                'otp_token_status'  => 1,
            ]);

            $token =  Auth::guard('merchant_api')->claims(['name' => 'beaconcourier'])->login($merchant);

            $service_area_charges =  auth()->guard('merchant_api')->user()->service_area_charges;

            $new_service_area = [];

            foreach($service_area_charges as $service_area_charge){
                $new_service_area[] = [
                    'name' => $service_area_charge->name,
                    'cod_charge' => $service_area_charge->cod_charge,
                    'cod_charge' => $service_area_charge->cod_charge,
                    'weight_type' => $service_area_charge->weight_type,
                    'charge' => $service_area_charge->pivot->charge,
                ];
            }

            unset(
                $merchant->service_area_charges,
                $merchant->store_password,
                $merchant->created_admin_id,
                $merchant->updated_admin_id,
                $merchant->created_at,
                $merchant->updated_at
            );

            $cod_charge = $merchant->cod_charge;
            if(isNull($merchant->cod_charge)){
                $cod_charge = -1;
            }

            return response()->json([
                'success'  => 200,
                'message'  => "Merchant Login Successfully",
                'token'    => $token,
                'merchant' => $merchant,
                'cod_charge_percent' => $cod_charge,
                'service_area_charges' => $new_service_area,
            ], 200);
        }


        return response()->json([
            'success' => 401,
            'message' => "Confirm Token Doesn't Matched",
            'error'   => "Unauthorized",
        ], 401);
    }

    /**
     * Get the authenticated User.
     *@param  Illuminate\Http\Request

     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request) {
        $merchant                       =  auth()->guard('merchant_api')->user();

        if($merchant->image){
            $merchant->image = asset('uploads/merchant/'.$merchant->image);
        }
        else{
            $merchant->image = asset('image/defaultMerchant.png');
        }

        if($merchant->trade_license){
            $merchant->trade_license = asset('uploads/merchant/'.$merchant->trade_license);
        }
        if($merchant->nid_card){
            $merchant->nid_card = asset('uploads/merchant/'.$merchant->nid_card);
        }
        if($merchant->tin_certificate){
            $merchant->tin_certificate = asset('uploads/merchant/'.$merchant->tin_certificate);
        }

        $service_area_charges           =  auth()->guard('merchant_api')->user()->service_area_charges;
        $service_area_return_charges    =  auth()->guard('merchant_api')->user()->service_area_return_charges;

        $merchant_service_area_cod_charges    =  auth()->guard('merchant_api')->user()->merchant_service_area_cod_charges;
        $new_service_area           = [];
        $new_service_return_area    = [];
// return $merchant_service_area_cod_charges;

        foreach($service_area_charges as $service_area_charge){
            
            $new_service_area[] = [
                'name' => $service_area_charge->name,
                'cod_charge' => $service_area_charge->cod_charge,
                'weight_type' => $service_area_charge->weight_type,
                'charge' => $service_area_charge->pivot->charge,
            ];
        }
        foreach($service_area_return_charges as $service_area_return_charge){
            $new_service_return_area[] = [
                'name' => $service_area_charge->name,
                'return_charge' => $service_area_charge->pivot->return_charge,
            ];
        }

        $cod_charge = 0;
        if($merchant->cod_charge){
            $cod_charge = $merchant->cod_charge;
        }

        unset(
            $merchant->service_area_charges,
            $merchant->service_area_return_charges,
            $merchant->cod_charge,
            $merchant->store_password,
            $merchant->created_admin_id,
            $merchant->updated_admin_id,
            $merchant->created_at,
            $merchant->updated_at
        );

        return response()->json([
            'success'       => 200,
            'message'       => "Merchant Logged in Information",
            'merchant'      => $merchant,
            'cod_charge_percent'      => $cod_charge,
            'service_area_charges'    => $new_service_area,
            'service_area_return_charges'    => $new_service_return_area,
            'token'         => $request->token,

        ], 200);
    }



    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(auth()->guard('merchant_api')->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'success'      => 200,
            'message'      => "Merchant New Token",
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->guard('merchant_api')->factory()->getTTL() * 60,
        ], 200);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function payload() {
        return auth()->guard('merchant_api')->payload();
    }


    public function forgotPassword(Request $request) {
        $Validator = Validator::make($request->all(), [
            'contact_number'    => 'required|numeric|digits:11',
            ],
            [
                'contact_number.required'   => 'Contact Number is Required',
                'contact_number.numeric'    => 'Contact Number must be Numeric',
            ]
        );

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }

        $contact_number = '0' . substr(preg_replace('/\D/', '', $request->input('contact_number')), -10);

        $merchant = Merchant::where("contact_number", $contact_number)->first();

        if($merchant){

            $otp_token = random_int(100000,999999);

            Merchant::where('id', $merchant->id)->update([
                'otp_token'         => $otp_token,
                'otp_token_created' => date("Y-m-d H:i:s"),
                'otp_token_status'  => 0,
            ]);

            $message    = "Dear {$merchant->company_name}, ";
            $message    .= "Your OTP is {$otp_token}. Please Confirm your account.";

            $this->send_sms($contact_number, $message);

            return response()->json([
                'success'  => 200,
                'message'  => "Merchant Forget Password OTP Has been send Successfully",
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Merchant user Credential not Match",
            'error'   => "Unauthorized",
        ], 401);

    }


    public function confirmForgotPassword(Request $request) {
        $Validator = Validator::make($request->all(), [
            'contact_number'    => 'required|numeric|digits:11',
            // 'otp_token'         => 'required|numeric|digits:6',
            'password'          => 'required|min:5',
            ],
            [
                'contact_number.required'   => 'Contact number is Required',
                'contact_number.min'        => 'Contact number is minimum 11 Digit',
                // 'otp_token.required'        => 'OTP Token is Required',
                // 'otp_token.min'             => 'OTP Token is minimum 11 Digit',
                'password.required'         => 'Password is Required',
                'password.min'              => 'Password is minimum 5 Digit Required',
            ]
        );

        if ($Validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $Validator->errors(),
            ], 401);
        }

        $contact_number = '0' . substr(preg_replace('/\D/', '', $request->input('contact_number')), -10);

        $merchant = Merchant::where([
            "contact_number"    => $contact_number,
            // "otp_token"         =>  $request->input('otp_token')
        ])->first();

        if($merchant){

            Merchant::where('id', $merchant->id)->update([
                'otp_token'         => null,
                'otp_token_status'  => 1,
                'password'          => bcrypt($request->input('password')),
                'store_password'    => $request->input('password'),
            ]);

            $token =  Auth::guard('merchant_api')->claims(['name' => 'beaconcourier'])->login($merchant);

            $service_area_charges =  auth()->guard('merchant_api')->user()->service_area_charges;

            $new_service_area = [];

            foreach($service_area_charges as $service_area_charge){
                $new_service_area[] = [
                    'name' => $service_area_charge->name,
                    'cod_charge' => $service_area_charge->cod_charge,
                    'cod_charge' => $service_area_charge->cod_charge,
                    'weight_type' => $service_area_charge->weight_type,
                    'charge' => $service_area_charge->pivot->charge,
                ];
            }

            unset(
                $merchant->service_area_charges,
                $merchant->store_password,
                $merchant->created_admin_id,
                $merchant->updated_admin_id,
                $merchant->created_at,
                $merchant->updated_at
            );

            $cod_charge = $merchant->cod_charge;
            if(isNull($merchant->cod_charge)){
                $cod_charge = -1;
            }

            return response()->json([
                'success'  => 200,
                'message'  => "Merchant Login Successfully",
                'token'    => $token,
                'merchant' => $merchant,
                'cod_charge_percent' => $cod_charge,
                'service_area_charges' => $new_service_area,

            ], 200);
        }


        return response()->json([
            'success' => 401,
            'message' => "Confirm Token Doesn't Matched",
            'error'   => "Unauthorized",
        ], 401);
    }

    public function profileUpdate(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'company_name'      => 'required',
            'name'              => 'required',
            'email'             => 'required|email|unique:merchants,email,' . $merchant_id,
            'image'             => 'sometimes|image|max:3000',
            'contact_number'    => 'required',
            'address'           => 'required',
            'business_address'  => 'sometimes',
            'bkash_number'      => 'sometimes',
            'nagad_number'      => 'sometimes',
            'fb_url'            => 'sometimes',
            'bank_name'         => 'sometimes',
            'bank_account_no'   => 'sometimes',
            'bank_route_no'     => 'sometimes',
            'bank_branch_name'  => 'sometimes',
            'bank_account_name' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $merchant       = Merchant::find($merchant_id);



        $image_name      = $merchant->image;
        if ($request->hasFile('image')) {
            $image_name = $this->uploadFile($request->file('image'), '/merchant/');

            if (!empty($merchant->image)) {
                $old_image_path = str_replace('\\', '/', public_path()) . '/uploads/merchant/' . $merchant->image;

                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }

            }

        }

        $data = [
            'company_name'      => $request->input('company_name'),
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'contact_number'    => $request->input('contact_number'),
            'image'             => $image_name,
            'address'           => $request->input('address'),
            'business_address'  => $request->input('business_address'),
            'bkash_number'      => $request->input('bkash_number'),
            'nagad_number'      => $request->input('nagad_number'),
            'fb_url'            => $request->input('fb_url'),
            'bank_name'         => $request->input('bank_name'),
            'bank_account_no'   => $request->input('bank_account_no'),
            'bank_branch_name'  => $request->input('bank_branch_name'),
            'bank_route_no'     => $request->input('bank_route_no'),
            'bank_account_name' => $request->input('bank_account_name'),
        ];


        $check = Merchant::where('id', $merchant_id)
            ->update($data);


        if ($check) {
            $merchant       = Merchant::find($merchant_id);

            return response()->json([
                'success'   => 200,
                'merchant'   => $merchant,
                'message'   => "Merchant Profile Information Update Successfully",
                'parcel_id' => $request->parcel_id,
            ], 200);
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Merchant Profile Information Update Unsuccessfully",
            ], 401);
        }
    }

    public function updatePassword(Request $request) {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'current_password'  => 'required|min:5',
            'password'          => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $merchant       = Merchant::find($merchant_id);



        if ($merchant->store_password != $request->input('current_password')) {
            return response()->json([
                'success' => 401,
                'message' => "Your Current Password does not match",
                'error'   => $validator->errors(),
            ], 401);
        }

        $data = [
            'password'          => bcrypt($request->input('password')),
            'store_password'    => $request->input('password'),
        ];


        $check = Merchant::where('id', $merchant_id)
            ->update($data);


        if ($check) {
            $merchant       = Merchant::find($merchant_id);

            return response()->json([
                'success'   => 200,
                'merchant'   => $merchant,
                'message'   => "Merchant Password Update Successfully",
                'parcel_id' => $request->parcel_id,
            ], 200);

        } else {
            return response()->json([
                'success' => 401,
                'message' => "Merchant Password Update Unsuccessfully",
            ], 401);
        }
    }
}

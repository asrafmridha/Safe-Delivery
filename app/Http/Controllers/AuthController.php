<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseUser;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Mail\AdminPasswordRestMail;
use App\Models\Merchant;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller{

    public function login(){
        // if (auth()->guard('admin')->check()) {
        //     $this->setMessage('Login Successfully', 'success');
        //     return redirect()->route('admin.home');
        // }


        return view('frontend.login');
    }

    public function login_check(Request $request) {
        $Validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required|min:5',
            // 'user_type' => 'required',
        ],
        [
            'email.required'    => 'Email is Required',
            'password.required' => 'Password is Required',
            'password.min'      => 'Password is Required',
            // 'user_type.required'      => 'Please Select Any One Type User',
        ]);

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        $user_name = "email";
        if(is_numeric($request->input('email'))){
            $user_name  = "contact_number";
        }

        /** Admin Login Without OTP */
        if(auth()->guard('admin')->attempt([
            $user_name     => $request->post('email'),
            'password'  => $request->input('password'),
            'status'    => 1,
        ])){

            $this->setApplicationInformationIntoSession();
            $this->setMessage('Admin Login Successfully', 'success');
            return redirect()->route('admin.home');

        }

        /** Admin Login With OTP */
//        if(auth()->guard('admin')->attempt([
//            $user_name     => $request->post('email'),
//            'password'  => $request->input('password'),
//            'status'    => 1,
//        ])){
//
//            $otp_token      = random_int(100000,999999);
//            $user = auth()->guard('admin')->user();
//            $data_array = [
//                'otp_token' => $otp_token,
//                'otp_token_created' => date("Y-m-d H:i:s"),
//                'otp_token_status'  => 0
//            ];
//
//            $message    = "Dear {$user->name}, ";
//            $message    .= "Your OTP is {$otp_token}. Please confirm OTP for login.";
//
//            if($this->send_sms($user->contact_number, $message)){
//
//                $user_update = $user->update($data_array);
//                if($user_update) {
//                    return redirect()->route("frontend.otp_login");
//                }else{
//                    $this->setMessage('Something went wrong, please try again!', 'danger');
//                    return redirect()->route('frontend.login');
//                }
//
//            }else{
//                $this->setMessage('Something went wrong, please try again!', 'danger');
//                return redirect()->route('frontend.login');
//            }
//
//        }

        elseif(auth()->guard('branch')->attempt([
            $user_name => $request->post('email'),
            'password' => $request->input('password'),
            'status' => 1,
        ])){
            $this->setApplicationInformationIntoSession();
            $this->setMessage('Branch Login Successfully', 'success');
            return redirect()->route('branch.home');
        }

                /** Branch login with OTP */
//        elseif(auth()->guard('branch')->attempt([
//            $user_name => $request->post('email'),
//            'password' => $request->input('password'),
//            'status' => 1,
//        ])){
//
//            $otp_token      = random_int(100000,999999);
//            $user = auth()->guard('branch')->user();
//            $data_array = [
//                'otp_token' => $otp_token,
//                'otp_token_created' => date("Y-m-d H:i:s"),
//                'otp_token_status'  => 0
//            ];
//
//            $message    = "Dear {$user->name}, ";
//            $message    .= "Your OTP is {$otp_token}. Please confirm OTP for login.";
//
//            if($this->send_sms($user->contact_number, $message)){
//
//                $user_update = $user->update($data_array);
//                if($user_update) {
//                    return redirect()->route("frontend.otp_login");
//                }else{
//                    $this->setMessage('Something went wrong, please try again!', 'danger');
//                    return redirect()->route('frontend.login');
//                }
//
//            }else{
//                $this->setMessage('Something went wrong, please try again!', 'danger');
//                return redirect()->route('frontend.login');
//            }
//
//        }



        elseif(auth()->guard('merchant')->attempt([
            $user_name => $request->post('email'),
            'password' => $request->input('password'),
            'status' => 1,
        ])){
            $this->setApplicationInformationIntoSession();
            $this->setMessage('Merchant Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }
         elseif(auth()->guard('merchant')->attempt([
            $user_name => $request->post('email'),
            'password' => $request->input('password'),
            'status' => 0,
        ])){
             $this->setMessage('Your account is currently pending approval by the administrator.', 'danger');
             return redirect()->route('frontend.login');
            
            
        }
        elseif(auth()->guard('rider')->attempt([
            $user_name => $request->post('email'),
            'password' => $request->input('password'),
            'status' => 1,
        ])){
            $this->setApplicationInformationIntoSession();
            $this->setMessage('Rider Login Successfully', 'success');
            return redirect()->route('rider.home');
        }

        /** Warehouse login without OTP */
        elseif(auth()->guard('warehouse')->attempt([
            $user_name => $request->post('email'),
            'password' => $request->input('password'),
            'status' => 1,
        ])){
            $this->setApplicationInformationIntoSession();
            $this->setMessage('Warehouse Login Successfully', 'success');
            return redirect()->route('warehouse.home');

        }

        /** Warehouse login with OTP */
//        elseif(auth()->guard('warehouse')->attempt([
//            $user_name => $request->post('email'),
//            'password' => $request->input('password'),
//            'status' => 1,
//        ])){
//
//            $otp_token      = random_int(100000,999999);
//            $user = auth()->guard('warehouse')->user();
//            $data_array = [
//                'otp_token' => $otp_token,
//                'otp_token_created' => date("Y-m-d H:i:s"),
//                'otp_token_status'  => 0
//            ];
//
//            $message    = "Dear {$user->name}, ";
//            $message    .= "Your OTP is {$otp_token}. Please confirm OTP for login.";
//
//            if($this->send_sms($user->contact_number, $message)){
//
//                $user_update = $user->update($data_array);
//                if($user_update) {
//                    return redirect()->route("frontend.otp_login");
//                }else{
//                    $this->setMessage('Something went wrong, please try again!', 'danger');
//                    return redirect()->route('frontend.login');
//                }
//
//            }else{
//                $this->setMessage('Something went wrong, please try again!', 'danger');
//                return redirect()->route('frontend.login');
//            }
//
//        }

        $this->setMessage('Credential Does not to Any type of User', 'danger');
        return redirect()->route('frontend.login');
    }


    /** For Login OTP Checking */
    public function otp_login(){
        // if (auth()->guard('admin')->check()) {
        //     $this->setMessage('Login Successfully', 'success');
        //     return redirect()->route('admin.home');
        // }

        $application = Application::first();

        return view('frontend.otp_login', compact('application'));
    }

    public function otp_check(Request $request) {
        $Validator = Validator::make($request->all(), [
            'otp_token'    => 'required|min:6|max:6',
        ],
        [
            'otp_token.required'    => 'Your OTP is Required',
        ]);

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }


        if(auth()->guard('admin')->user() ) {

            $adminUser      = auth()->guard('admin')->user();
            $check_admin    = Admin::where('contact_number', $adminUser->contact_number)
                                ->where('otp_token', $request->input('otp_token'))->first();

            $data_update = [
                'otp_token' => NULL,
                'otp_token_created' => NULL,
                'otp_token_status'  => 1
            ];

            $current_date_time = Carbon::now();
            $timeMinutes = $current_date_time->diffInMinutes($adminUser->otp_token_created);

            if($timeMinutes <= 5) {

                if($check_admin) {

                    $updateUser = $adminUser->update($data_update);
                    if($updateUser) {
                        $this->setApplicationInformationIntoSession();
                        $this->setMessage('Admin Login Successfully', 'success');
                        return redirect()->route('admin.home');
                    }else{
                        $this->setMessage('Something went wrong, please try again! Please try again.', 'danger');
                        return redirect()->route('frontend.login');
                    }
                }else{
                    $this->setMessage('Your OTP does not match!', 'danger');
                    return redirect()->route('frontend.otp_login');
                }

            }else{
                $this->setMessage('Your OTP has been expired! Please try again.', 'danger');
                return redirect()->route('frontend.login');
            }


        }

        /** Warehouse login otp check */
        elseif (auth()->guard('warehouse')->user()) {

            $warehouseUser = auth()->guard('warehouse')->user();
            $check_admin = WarehouseUser::where('contact_number', $warehouseUser->contact_number)
                ->where('otp_token', $request->input('otp_token'))->first();


            $data_update = [
                'otp_token' => NULL,
                'otp_token_created' => NULL,
                'otp_token_status'  => 1
            ];

            $current_date_time = Carbon::now();
            $timeMinutes = $current_date_time->diffInMinutes($warehouseUser->otp_token_created);

            if($timeMinutes <= 5) {

                if($check_admin) {

                    $updateUser = $warehouseUser->update($data_update);
                    if($updateUser) {
                        $this->setApplicationInformationIntoSession();
                        $this->setMessage('Ware Login Successfully', 'success');
                        return redirect()->route('warehouse.home');
                    }else{
                        $this->setMessage('Something went wrong, please try again! Please try again.', 'danger');
                        return redirect()->route('frontend.login');
                    }
                }else{
                    $this->setMessage('Your OTP does not match!', 'danger');
                    return redirect()->route('frontend.otp_login');
                }

            }else{
                $this->setMessage('Your OTP has been expired! Please try again.', 'danger');
                return redirect()->route('frontend.login');
            }

        }
        else{
            $check_merchant    = Merchant::where('otp_token', $request->input('otp_token'))->first();

            return redirect()->route('frontend.login');
        }

    }




    /** For Login OTP Checking */
    public function otp_merchant_registration_login(){
        // if (auth()->guard('admin')->check()) {
        //     $this->setMessage('Login Successfully', 'success');
        //     return redirect()->route('admin.home');
        // }

        $application = Application::first();

        return view('frontend.otp_merchant_registration_login', compact('application'));
    }

    public function otp_merchant_registration_check(Request $request) {
        $Validator = Validator::make($request->all(), [
            'otp_token'    => 'required|min:6|max:6',
        ],
        [
            'otp_token.required'    => 'Your OTP is Required',
        ]);

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        $merchant    = Merchant::where('otp_token', $request->input('otp_token'))->first();
        if($merchant){
            Merchant::where('id', $merchant->id)->update([
                'status'            => 1,
                'otp_token'         => null,
                'otp_token_status'  => 1,
            ]);

            auth()->guard('merchant')->login($merchant);

            $this->setApplicationInformationIntoSession();
            $this->setMessage('Merchant Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }
        else{
            $this->setMessage('OTP does not match', 'danger');
            return redirect()->back();
        }
    }


    public function logout() {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function forgotPassword(){
        if (auth()->guard('admin')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('admin.home');
        }
        $application = Application::first();

        return view('admin.forgotPassword', compact('application'));
    }

    public function confirmForgotPassword(Request $request) {
        $Validator = Validator::make($request->all(), [
                'email'    => 'required|email',
            ],
            [
                'email.required'    => 'Email is Required',
                'email.email'       => 'Email is not Valid Email',
            ]
        );

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        $admin = Admin::where('email', $request->post('email'))->first();

        if (!empty($admin)) {

            $application = Application::first();
            $token = $this->generateRandomString(70);

            $data = [
                'email'         => $request->post('email'),
                'token'         => strtotime(date('Y-m-d H:i:s')).$this->generateRandomString(70),
                'type'          => 1,

                'date_time'     => date('Y-m-d H:i:s'),
            ];
            \DB::table('password_resets')->insert($data);

            $data['admin_name'] = $admin->name;

            $application = Application::first();

            // return new AdminPasswordRestMail($data, $application);

            Mail::to($request->post('email'))->send(new AdminPasswordRestMail($data, $application));

            $this->setMessage('Send your Password Reset Link Successfully', 'success');
        } else {
            $this->setMessage('This email not valid Admin..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    public function resetPassword(Request $request, $token) {
        if (auth()->guard('admin')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('admin.home');
        }

        $adminResetPasswordData = \DB::table('password_resets')->where('token', $token)->first();
        if(!empty($adminResetPasswordData)){
            $checkTime      = abs((strtotime(date("Y-m-d H:i:s")) - strtotime($adminResetPasswordData->date_time))/60);
            $verification   = $adminResetPasswordData->verification_type;
            $application    = Application::first();
            $admin          = Admin::where('email', $adminResetPasswordData->email)->first();
            return view('admin.resetPassword', compact('application', 'token', 'admin', 'checkTime', 'verification'));
        }

        return redirect()->route('frontend.home');
    }

    public function confirmResetPassword(Request $request) {
        $Validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:5',
        ],
        [
            'email.required'    => 'Email is Required',
            'email.email'       => 'Email is Required',
            'password.required' => 'Password is Required',
            'password.min'      => 'Password is minimum 5 Digit Required',
        ]);

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        $data = [
            'password' => bcrypt($request->input('password')),
        ];
        $admin                  = Admin::where('email', $request->post('email'))->update($data);

        if (!empty($admin)) {

            if (auth()->guard('admin')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $application = Application::first();
                $session_data = [
                    'company_name'           => $application->name,
                    'company_email'          => $application->email,
                    'company_address'        => $application->address,
                    'company_contact_number' => $application->contact_number,
                    'company_photo'          => $application->photo,
                ];
                session()->put($session_data);

                $this->setMessage('Login Successfully', 'success');

                \DB::table('password_resets')->where('token', $request->input('token'))
                    ->update([
                        'verification_type' => 1
                    ]);

                return redirect()->route('admin.home');

            } else {
                $this->setMessage('Login Failed', 'danger');
                return redirect()->back()->withInput();
            }

         }
         else {
            $this->setMessage('This email not valid Admin..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    public function setApplicationInformationIntoSession(){
        $application = Application::first();
        $session_data = [
            'company_name'           => $application->name,
            'company_email'          => $application->email,
            'company_address'        => $application->address,
            'company_contact_number' => $application->contact_number,
            'company_photo'          => $application->photo,
        ];
        session()->put($session_data);
    }


}

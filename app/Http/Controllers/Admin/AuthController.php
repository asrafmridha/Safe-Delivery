<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Mail\AdminPasswordRestMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller{

    public function login(){
        if (auth()->guard('admin')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('admin.home');
        }
        $application = Application::first();
        return view('admin.login', compact('application'));
    }

    public function login_check(Request $request) {
        $Validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:5',
        ],
            [
                'email.required'    => 'Email is Required',
                'email.email'       => 'Email is Required',
                'password.required' => 'Password is Required',
                'password.min'      => 'Password is Required',
            ]);

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

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
        } else {
            $this->setMessage('Login Failed', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->route('admin.home');
    }

    public function logout() {
        auth()->guard('admin')->logout();
        $this->setMessage('Admin Logout Successfully', 'success');
        return redirect()->route('frontend.login');
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
            'password'          => bcrypt($request->input('password')),
            'store_password'    => $request->input('password'),
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


}

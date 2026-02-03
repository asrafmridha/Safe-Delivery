<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Mail\MerchantPasswordRestMail;
use App\Models\Application;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {

    public function login() {

        if (auth()->guard('merchant')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }

        $application = Application::first();
        return view('merchant.login', compact('application'));
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

        if (auth()->guard('merchant')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {

            $application = Application::first();

            $session_data = [
                'company_name'           => $application->name,
                'company_email'          => $application->email,
                'company_address'        => $application->address,
                'company_contact_number' => $application->contact_number,
                'company_photo'          => $application->photo,
            ];
            session()->put($session_data);

            $this->setMessage('Merchant Login Successfully', 'success');
        } else {
            $this->setMessage('Login Failed', 'danger');
            return redirect()->back()->withInput();
        }

        return redirect()->route('merchant.home');
    }

    public function forgotPassword() {

        if (auth()->guard('merchant')->check()) {
            $this->setMessage('Merchant Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }

        $application = Application::first();
        return view('merchant.forgotPassword', compact('application'));
    }

    public function confirmForgotPassword(Request $request) {
        $Validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ],
            [
                'email.required' => 'Email is Required',
                'email.email'    => 'Email is not Valid Email',
            ]
        );

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        $merchant = Merchant::where('email', $request->post('email'))->first();

        if (!empty($merchant)) {

            $application = Application::first();
            $token       = $this->generateRandomString(70);

            $data = [
                'email'     => $request->post('email'),
                'token'     => strtotime(date('Y-m-d H:i:s')) . $this->generateRandomString(70),
                'type'      => 3,

                'date_time' => date('Y-m-d H:i:s'),
            ];
            \DB::table('password_resets')->insert($data);

            $data['merchant_name'] = $merchant->name;

            $application = Application::first();

            // return new MerchantPasswordRestMail($data, $application);

            Mail::to($request->post('email'))->send(new MerchantPasswordRestMail($data, $application));

            $this->setMessage('Send your Password Reset Link Successfully', 'success');
        } else {
            $this->setMessage('This email not valid Merchant..', 'danger');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    public function resetPassword(Request $request, $token) {

        if (auth()->guard('merchant')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('merchant.home');
        }

        $merchantResetPasswordData = \DB::table('password_resets')->where('token', $token)->first();

        if (!empty($merchantResetPasswordData)) {
            $checkTime    = abs((strtotime(date("Y-m-d H:i:s")) - strtotime($merchantResetPasswordData->date_time)) / 60);
            $verification = $merchantResetPasswordData->verification_type;
            $application  = Application::first();
            $merchant     = Merchant::where('email', $merchantResetPasswordData->email)->first();
            return view('merchant.resetPassword', compact('application', 'token', 'merchant', 'checkTime', 'verification'));
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
            'password'       => bcrypt($request->input('password')),
            'store_password' => $request->input('password'),
        ];
        $merchant = Merchant::where('email', $request->post('email'))->update($data);

        if (!empty($merchant)) {

            if (auth()->guard('merchant')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $application  = Application::first();
                $session_data = [
                    'company_name'           => $application->name,
                    'company_email'          => $application->email,
                    'company_address'        => $application->address,
                    'company_contact_number' => $application->contact_number,
                    'company_photo'          => $application->photo,
                ];
                session()->put($session_data);

                $this->setMessage('merchant Login Successfully', 'success');

                \DB::table('password_resets')->where('token', $request->input('token'))
                    ->update([
                        'verification_type' => 1,
                    ]);

                return redirect()->route('merchant.home');

            } else {
                $this->setMessage('Login Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } else {
            $this->setMessage('This email not valid Merchant..', 'danger');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    public function logout() {
        auth()->guard('merchant')->logout();
        $this->setMessage('Merchant Logout Successfully', 'success');
        return redirect()->route('frontend.login');
    }

    public function home() {
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Home';
        return view('merchant.home', $data);
    }

}

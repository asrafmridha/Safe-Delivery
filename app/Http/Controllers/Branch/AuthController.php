<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Merchant;
use App\Models\Branch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BranchPasswordRestMail;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\BranchUser;

class AuthController extends Controller{

    public function login(){
        if (auth()->guard('branch')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('branch.home');
        }
        $application = Application::first();
        return view('branch.login', compact('application'));
    }


    public function login_check(Request $request) {
        $Validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|min:5',
            ], [
                'email.required'    => 'Email is Required',
                'email.email'       => 'Email is Required',
                'password.required' => 'Password is Required',
                'password.min'      => 'Password is Required',
            ]
        );

        if ($Validator->fails()) {
            return redirect()->back()->withInput()->withErrors($Validator);
        }

        if (auth()->guard('branch')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
            $application = Application::first();
            $session_data = [
                'company_name'           => $application->name,
                'company_email'          => $application->email,
                'company_address'        => $application->address,
                'company_contact_number' => $application->contact_number,
                'company_photo'          => $application->photo,
            ];
            session()->put($session_data);
            $this->setMessage('Branch Login Successfully', 'success');
        } else {
            $this->setMessage('Login Failed', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->route('branch.home');
    }


    public function logout() {
        auth()->guard('branch')->logout();
        $this->setMessage('Branch Logout Successfully', 'success');
        return redirect()->route('frontend.login');
    }


    public function forgotPassword(){
        if (auth()->guard('branch')->check()) {
            $this->setMessage('Branch Login Successfully', 'success');
            return redirect()->route('branch.home');
        }
        $application = Application::first();
        return view('branch.forgotPassword', compact('application'));
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

        $branch = Branch::where('email', $request->post('email'))->first();

        if (!empty($branch)) {
            $application = Application::first();
            $token = $this->generateRandomString(70);

            $data = [
                'email'         => $request->post('email'),
                'token'         => strtotime(date('Y-m-d H:i:s')).$this->generateRandomString(70),
                'type'          => 2,
                'date_time'     => date('Y-m-d H:i:s'),
            ];
            \DB::table('password_resets')->insert($data);

            $data['branch_name'] = $branch->name;

            $application = Application::first();

            // return new BranchPasswordRestMail($data, $application);
            Mail::to($request->post('email'))->send(new BranchPasswordRestMail($data, $application));

            $this->setMessage('Send your Password Reset Link Successfully', 'success');
        } else {
            $this->setMessage('This email not valid Branch..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }


    public function resetPassword(Request $request, $token) {
        if (auth()->guard('branch')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('branch.home');
        }

        $branchResetPasswordData = \DB::table('password_resets')->where('token', $token)->first();
        if(!empty($branchResetPasswordData)){
            $checkTime      = abs((strtotime(date("Y-m-d H:i:s")) - strtotime($branchResetPasswordData->date_time))/60);
            $verification   = $branchResetPasswordData->verification_type;
            $application    = Application::first();
            $branch          = BranchUser::where('email', $branchResetPasswordData->email)->first();
            return view('branch.resetPassword', compact('application', 'token', 'branch', 'checkTime', 'verification'));
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
        $branch                  = BranchUser::where('email', $request->post('email'))->update($data);

        if (!empty($branch)) {

            if (auth()->guard('branch')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $application = Application::first();
                $session_data = [
                    'company_name'           => $application->name,
                    'company_email'          => $application->email,
                    'company_address'        => $application->address,
                    'company_contact_number' => $application->contact_number,
                    'company_photo'          => $application->photo,
                ];
                session()->put($session_data);

                $this->setMessage('branch Login Successfully', 'success');

                \DB::table('password_resets')->where('token', $request->input('token'))
                    ->update([
                        'verification_type' => 1
                    ]);

                return redirect()->route('branch.home');

            } else {
                $this->setMessage('Login Failed', 'danger');
                return redirect()->back()->withInput();
            }

         }
         else {
            $this->setMessage('This email not valid Branch..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }


}

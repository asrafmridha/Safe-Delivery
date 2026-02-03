<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Merchant;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\WarehouseUser;
use App\Mail\WarehousePasswordRestMail;

class AuthController extends Controller{

    public function login(){
        if (auth()->guard('warehouse')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('warehouse.home');
        }
        $application = Application::first();
        return view('warehouse.login', compact('application'));
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

        if (auth()->guard('warehouse')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {

            $application = Application::first();
            $session_data = [
                'company_name'           => $application->name,
                'company_email'          => $application->email,
                'company_address'        => $application->address,
                'company_contact_number' => $application->contact_number,
                'company_photo'          => $application->photo,
            ];
            session()->put($session_data);

            $this->setMessage('Warehouse Login Successfully', 'success');
        } else {
            $this->setMessage('Login Failed', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->route('warehouse.home');
    }


    public function logout() {
        auth()->guard('warehouse')->logout();
        $this->setMessage('Werehouse Logout Successfully', 'success');
        return redirect()->route('frontend.login');
    }


    public function forgotPassword(){
        if (auth()->guard('warehouse')->check()) {
            $this->setMessage('Warehouse Login Successfully', 'success');
            return redirect()->route('warehouse.home');
        }
        $application = Application::first();
        return view('warehouse.forgotPassword', compact('application'));
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

        $warehouse = Warehouse::where('email', $request->post('email'))->first();

        if (!empty($warehouse)) {
            $application = Application::first();
            $token = $this->generateRandomString(70);

            $data = [
                'email'         => $request->post('email'),
                'token'         => strtotime(date('Y-m-d H:i:s')).$this->generateRandomString(70),
                'type'          => 2,
                'date_time'     => date('Y-m-d H:i:s'),
            ];
            \DB::table('password_resets')->insert($data);

            $data['warehouse_name'] = $warehouse->name;

            $application = Application::first();

            // return new WarehousePasswordRestMail($data, $application);
            Mail::to($request->post('email'))->send(new WarehousePasswordRestMail($data, $application));

            $this->setMessage('Send your Password Reset Link Successfully', 'success');
        } else {
            $this->setMessage('This email not valid Warehouse..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }


    public function resetPassword(Request $request, $token) {
        if (auth()->guard('warehouse')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('warehouse.home');
        }

        $warehouseResetPasswordData = \DB::table('password_resets')->where('token', $token)->first();
        if(!empty($warehouseResetPasswordData)){
            $checkTime      = abs((strtotime(date("Y-m-d H:i:s")) - strtotime($warehouseResetPasswordData->date_time))/60);
            $verification   = $warehouseResetPasswordData->verification_type;
            $application    = Application::first();
            $warehouse          = WarehouseUser::where('email', $warehouseResetPasswordData->email)->first();
            return view('warehouse.resetPassword', compact('application', 'token', 'warehouse', 'checkTime', 'verification'));
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
        $warehouse                  = WarehouseUser::where('email', $request->post('email'))->update($data);

        if (!empty($warehouse)) {

            if (auth()->guard('warehouse')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $application = Application::first();
                $session_data = [
                    'company_name'           => $application->name,
                    'company_email'          => $application->email,
                    'company_address'        => $application->address,
                    'company_contact_number' => $application->contact_number,
                    'company_photo'          => $application->photo,
                ];
                session()->put($session_data);

                $this->setMessage('Warehouse Login Successfully', 'success');

                \DB::table('password_resets')->where('token', $request->input('token'))
                    ->update([
                        'verification_type' => 1
                    ]);

                return redirect()->route('warehouse.home');

            } else {
                $this->setMessage('Login Failed', 'danger');
                return redirect()->back()->withInput();
            }

         }
         else {
            $this->setMessage('This email not valid Warehouse..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }


}

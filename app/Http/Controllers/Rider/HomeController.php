<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Rider;
use Illuminate\Support\Facades\Validator;
use App\Mail\RiderPasswordRestMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller{

    public function login(){
        if (auth()->guard('rider')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('rider.home');
        }
        $application = Application::first();
        return view('rider.login', compact('application'));
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

        if (auth()->guard('rider')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {

            $application = Application::first();
            $session_data = [
                'company_name'           => $application->name,
                'company_email'          => $application->email,
                'company_address'        => $application->address,
                'company_contact_number' => $application->contact_number,
                'company_photo'          => $application->photo,
            ];
            session()->put($session_data);

            $this->setMessage('Rider Login Successfully', 'success');
        } else {
            $this->setMessage('Login Failed', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->route('rider.home');
    }

    public function logout() {
        auth()->guard('rider')->logout();
        $this->setMessage('Rider Logout Successfully', 'success');
        return redirect()->route('frontend.login');
    }

    public function home() {
        $rider_id   = auth()->guard('rider')->id();

        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Home';


        /** E-courier */

        /** Today Pickup and Delivery Parcels */
        $data['todayPickupParcel']      = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (5,6,8)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayPickupComplete']    = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (10)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayPickupPending']     = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (8)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayPickupCancel']      = Parcel::whereRaw('pickup_rider_id = ? and pickup_rider_date = ? and status in (9)', [$rider_id, date("Y-m-d")])->select('id')->get();

        $data['todayDeliveryParcels']   = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (16,17,19)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayDeliveryComplete']  = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (21, 22)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayDeliveryPending']   = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (19)', [$rider_id, date("Y-m-d")])->select('id')->get();
        $data['todayDeliveryCancel']    = Parcel::whereRaw('delivery_rider_id = ? and delivery_rider_date = ? and status in (20)', [$rider_id, date("Y-m-d")])->select('id')->get();

        /** Total Pickup and Delivery Parcels */
        $data['totalPickupParcel']      = Parcel::whereRaw('pickup_rider_id = ? and status in (5,6,8)', [$rider_id])->select('id')->get();
        $data['totalPickupComplete']    = Parcel::whereRaw('pickup_rider_id = ? and status in (10)', [$rider_id])->select('id')->get();
        $data['totalPickupPending']     = Parcel::whereRaw('pickup_rider_id = ? and status in (8)', [$rider_id])->select('id')->get();
        $data['totalPickupCancel']      = Parcel::whereRaw('pickup_rider_id = ? and status in (9)', [$rider_id])->select('id')->get();

        $data['totalDeliveryParcels']   = Parcel::whereRaw('delivery_rider_id = ? ', [$rider_id])->select('id')->get();
        $data['totalDeliveryComplete']  = Parcel::whereRaw('delivery_rider_id = ? and status in (21, 22, 25)', [$rider_id])->select('id')->get();
        $data['totalDeliveryPending']   = Parcel::whereRaw('delivery_rider_id = ? and status in (23)', [$rider_id])->select('id')->get();
        $data['totalDeliveryCancel']    = Parcel::whereRaw('delivery_rider_id = ? and status in (20)', [$rider_id])->select('id')->get();

        $total_ecourier_collection          = Parcel::whereRaw('delivery_rider_id = ? and delivery_type in (1,2)', [$rider_id] )->sum('customer_collect_amount');
        $data['ecourierTotalCollectAmount'] = number_format((float) ($total_ecourier_collection), 2, '.', '');

        $ecourier_collection_paid_to_branch = Parcel::whereRaw('delivery_rider_id = ? and delivery_type in (1,2) and status >= ?', [$rider_id, 25] )->sum('customer_collect_amount');
        $data['ecourierPaidToBranch']       = number_format((float) $ecourier_collection_paid_to_branch, 2, '.', '');

        $data['ecourierBalanceCollectAmount'] = number_format((float) ($total_ecourier_collection - $ecourier_collection_paid_to_branch), 2, '.', '');

        return view('rider.home', $data);
    }

    public function profile() {
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Profile';
        $data['rider'] = Rider::with(['branch','district', 'upazila', 'area'])->where('id', auth()->guard('rider')->user()->id)->first();
        return view('rider.profile', $data);
    }



    public function forgotPassword(){
        if (auth()->guard('rider')->check()) {
            $this->setMessage('Rider Login Successfully', 'success');
            return redirect()->route('rider.home');
        }
        $application = Application::first();
        return view('rider.forgotPassword', compact('application'));
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

        $rider = Rider::where('email', $request->post('email'))->first();

        if (!empty($rider)) {

            $application = Application::first();
            $token = $this->generateRandomString(70);

            $data = [
                'email'         => $request->post('email'),
                'token'         => strtotime(date('Y-m-d H:i:s')).$this->generateRandomString(70),
                'type'          => 4,
                'date_time'     => date('Y-m-d H:i:s'),
            ];
            \DB::table('password_resets')->insert($data);

            $data['rider_name'] = $rider->name;

            $application = Application::first();

            // return new RiderPasswordRestMail($data, $application);

            Mail::to($request->post('email'))->send(new RiderPasswordRestMail($data, $application));

            $this->setMessage('Send your Password Reset Link Successfully', 'success');
        } else {
            $this->setMessage('This email not valid Rider..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    public function resetPassword(Request $request, $token) {
        if (auth()->guard('rider')->check()) {
            $this->setMessage('Login Successfully', 'success');
            return redirect()->route('rider.home');
        }

        $riderResetPasswordData = \DB::table('password_resets')->where('token', $token)->first();
        if(!empty($riderResetPasswordData)){
            $checkTime      = abs((strtotime(date("Y-m-d H:i:s")) - strtotime($riderResetPasswordData->date_time))/60);
            $verification   = $riderResetPasswordData->verification_type;
            $application    = Application::first();
            $rider          = Rider::where('email', $riderResetPasswordData->email)->first();
            return view('rider.resetPassword', compact('application', 'token', 'rider', 'checkTime', 'verification'));
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
        $rider                  = Rider::where('email', $request->post('email'))->update($data);

        if (!empty($rider)) {

            if (auth()->guard('rider')->attempt(['email' => $request->post('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $application = Application::first();
                $session_data = [
                    'company_name'           => $application->name,
                    'company_email'          => $application->email,
                    'company_address'        => $application->address,
                    'company_contact_number' => $application->contact_number,
                    'company_photo'          => $application->photo,
                ];
                session()->put($session_data);

                $this->setMessage('Rider Login Successfully', 'success');

                \DB::table('password_resets')->where('token', $request->input('token'))
                    ->update([
                        'verification_type' => 1
                    ]);

                return redirect()->route('rider.home');

            } else {
                $this->setMessage('Login Failed', 'danger');
                return redirect()->back()->withInput();
            }

         }
         else {
            $this->setMessage('This email not valid Rider..', 'danger');
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }


}

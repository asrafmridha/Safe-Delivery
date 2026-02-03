<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMerchantEmail;
use App\Models\Application;
use App\Models\EmailVerification;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{

    public function emailVerify($token)
    {
        $merchant_user  = auth()->guard('merchant')->user();
        $verifyEmail    = EmailVerification::where('token', $token)
                                            ->where('user_id', $merchant_user->id)
                                            ->where('type', 3)->first();
        if($verifyEmail) {

            if($verifyEmail->type == 3 && $verifyEmail->verify_status == 0) {

                $verifyEmail->merchants->email_verified_at = date("Y-m-d H:i:s");
                $verifyEmail->merchants->save();

                $verifyEmail->update([
                    'verify_status' => 1
                ]);

                $status = "Your e-mail successfully verified, use your account without any problem.
Keep in touch with us.";

            } else {
                $status = "Your e-mail is already verified, use your account without any problem.
Keep in touch with us.";
            }
        }else{
            $this->setMessage("Sorry your email cannot be identified.", 'danger');
            return abort(404);
        }
        $this->setMessage($status, 'success');
        return redirect()->route('merchant.emailVerifySuccess');

    }

    public function emailVerifySuccess()
    {
        $application = Application::first();
        return view('emails.EmailVerifySuccess', compact('application'));
    }

    public function emailVerificationLinkForMerchant(Request $request)
    {
        $merchant = auth()->guard('merchant')->user();
        \DB::beginTransaction();
        try {

            $data_verification  = [
                'token'         => $this->generateRandomString(70),
                'type'          => 3
            ];

            if ($merchant) {

                if($merchant->email_verifications) {
                    $merchant->email_verifications()->delete();
                }
                $email_verification = $merchant->email_verifications()->save(new EmailVerification($data_verification));

                \DB::commit();

                $merchant_data  = Merchant::where('id', $merchant->id)->first();
                $application = Application::first();
                Mail::to($merchant->email)->send(new VerifyMerchantEmail($merchant_data, $application));

                $this->setMessage('Your Verification link send successfully!', 'success');
                return redirect()->back();
            } else {
                $this->setMessage('Your Verification link send successfully!', 'danger');
                return redirect()->back();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Something went wrong, please try again!', 'danger');
            return redirect()->back();
        }
    }

}

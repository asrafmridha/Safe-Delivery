<?php

namespace App\Http\Controllers\API\Rider;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller {

    /**
     * Get a JWT via given credentials.
     *
     * @param  Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required|min:5',
            ],
            [
                'email.required'    => 'Email is Required',
                'password.required' => 'Password is Required',
                'password.min'      => 'Password is Minimum 5',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error'   => $validator->errors(),
            ], 401);
        }

        $user_name = "email";
        if(is_numeric($request->input('email'))){
            $user_name = "contact_number";
        }

        if ($token = auth()->guard('rider_api')->claims(['name' => 'mettroexpress'])->attempt([
                $user_name  => $request->input('email'),
                'password'  => $request->input('password'),
                'status'    => 1
        ])){
            $rider =  auth()->guard('rider_api')->user();

            if($rider->image){
                $rider->image = asset('uploads/rider/'.$rider->image);
            }

            unset(
                $rider->store_password,
                $rider->created_admin_id,
                $rider->updated_admin_id,
                $rider->created_at,
                $rider->updated_at
            );


            return response()->json([
                'success'  => 200,
                'message'  => "Rider Login Successfully",
                'token'    => $token,
                'rider' => $rider,
            ], 200);
        }

        return response()->json([
            'success' => 401,
            'message' => "Rider user Credential not Match",
            'error'   => "Unauthorized",
        ], 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('rider_api')->logout();

        return response()->json([
            'success' => 200,
            'message' => "Rider Successfully logged out",
        ], 200);

    }



    /**
     * Get the authenticated User.
     *@param  Illuminate\Http\Request

     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request) {
        $rider =  auth()->guard('rider_api')->user();

        if($rider->image){
            $rider->image = asset('uploads/rider/'.$rider->image);
        }

        unset(
            $rider->store_password,
            $rider->created_admin_id,
            $rider->updated_admin_id,
            $rider->created_at,
            $rider->updated_at
        );

        return response()->json([
            'success'       => 200,
            'message'       => "Rider Logged in Information",
            'rider'         => $rider,
            'token'         => $request->token,

        ], 200);
    }



    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(auth()->guard('rider_api')->refresh());
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
            'message'      => "Rider New Token",
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->guard('rider_api')->factory()->getTTL() * 60,
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
        return auth()->guard('rider_api')->payload();
    }

}

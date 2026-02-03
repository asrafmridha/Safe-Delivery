<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->renderable(function (TokenInvalidException $exception, $request) {
            return response()->json([
                'success'   => false,
                'error'     => 'Token is invalid'
            ],400);
        });

        $this->renderable(function (TokenExpiredException $exception, $request) {
            return response()->json([
                'success'   => false,
                'error'     => 'Token is Expired'
            ],400);
        });

        $this->renderable(function (JWTException $exception, $request) {
            return response()->json([
                'success'   => false,
                'error'     => 'There is problem with your token'
            ],400);
        });


        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

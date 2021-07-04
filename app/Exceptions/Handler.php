<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'current_password',
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            // if user is unauthenticated
            if($e instanceof AuthenticationException && $request->expectsJson()){
                return response()->json([
                    'alertType' => 'user-unauthenticated',
                    'message' => 'You are unauthenticated.'
                ], 401);
            }
            // if user exceeds the maximum number of attempt
            if($e instanceof ThrottleRequestsException && $request->expectsJson()){
                return response()->json([
                    'alertType' => 'too-many-attempts',
                    'message' => 'You have exceeded the maximum number of attempts. Please try again later.'
                ], 403);
            }
            // checks for maintainance mode
            if($e instanceof HttpException && $request->expectsJson()){
                return response()->json([
                    'alertType' => 'app-in-maintainance',
                    'message' => 'Our application is in maintainance mode. Try after some time.'
                ], 503);
            }
        });
    }
}

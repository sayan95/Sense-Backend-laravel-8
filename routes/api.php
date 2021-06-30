<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Customer\Therapist\Auth\{
    LoginController,
    RegisterController,
    ResendOTPNotificationController,
    ValidateRegisterOTPController
};


/**
 * ---------------------------------------
 *  therapist routes group               /
 *  auth protected routes                /
 *  auth-api                             /
 * ---------------------------------------
 */
Route::group([
    'prefix' => 'therapist/auth-api',
], function(){
    // guest routes
    Route::group([ 'middleware' => 'guest:therapist' ], function(){
        Route::post('/login', [LoginController::class, 'login'])->name('therapist.login');
        Route::post('/register', [RegisterController::class, 'register'])->name('therapist.register');
        Route::post('/auth/verify', [ValidateRegisterOTPController::class, 'verify'])->name('therapist.verify.email');
        Route::get('/resend/verify', [ResendOTPNotificationController::class, 'resend'])->name('therapist.verify.email.resend');
    });
    //auth protected routes
    // Route::group(['middleware' => 'auth:therapist'], function(){
    //     Route::post('/reset/password', 'ResetPasswordController@resetPassword')->name('therapist.reset.password');       // therapist-logout 
    //     Route::post('/logout', 'LogoutController@logout')->name('therapist.logout');       // therapist-logout 
    // });
}); 

/**
 * --------------------------------------------
 *  therapist routes group
 *  auth protected routes
 *  profile-api
 * --------------------------------------------
 */
// Route::group([
//     'prefix' => 'therapist/profile-api',
//     'namespace' => 'Web\User\Therapist\Profile',
// ], function(){
//     // auth protected routes
//     Route::group(['middleware' => 'auth:therapist'], function(){
//         Route::get('/profile', 'MeController@me')->name('therapist.profile');            // therapist-profile-index
//         Route::post('/create/profile/{email}', 'ProfileController@createProfile')->name('therapist.profile.create');    // therapist-profile-create
//     });
// });


/**
 * ---------------------------------------------
 *  app-service-api
 *  Provides application specific service datas
 * ----------------------------------------------
 */
// Route::group([
//     'prefix' => 'app/service-api',
//     'namespace' => 'Web\App'
// ], function(){
//     Route::get('/service/therapist', 'TherapistServiceSupportController@getTherapistServiceData')->name('app.therapist.service');
//     Route::get('/settings/info', 'AppSettingsController@index')->name('app.settings.info');
// });


/**
 * ----------------------------------------
 * Connection test api 
 * test api endpoint and database connection
 * -----------------------------------------
 */
// Route::group([
//     'prefix' => '/connection-api',
//     'namespace' => 'Web\Connection'
// ], function(){
//     Route::get('/test/connection/server', 'AppConnectioncontroller@checkAppConnection');
//     Route::get('/test/connection/db', 'DBConnectioncontroller@checkDBConnection');
// });

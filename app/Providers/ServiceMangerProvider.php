<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Classes\TherapistService;
use App\Services\Interfaces\ITherapistService;


class ServiceMangerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         *  binding the service interfaces with its concrete implementation
         */
        $this->app->bind(ITherapistService::class, TherapistService::class);
    }
}

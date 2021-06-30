<?php

namespace App\Providers;

use App\Repositories\Contracts\TherapistContract;
use App\Repositories\DAL\TherapistRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
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
         *  binding the contract with its concrete implementation
         */
        $this->app->bind(TherapistContract::class, TherapistRepository::class);
    }
}

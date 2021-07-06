<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    AgeListContract,
    DegreeListContract,
    ExpertiesListContract,
    LanguageListContract,
    SpectrumSpecializationListContract,
    TherapistContract,
    TherapyProfileListContract
};
use App\Repositories\DAL\{
    AgeListRepository,
    DegreeListRepository,
    ExpertiesListRepository,
    LanguageListRepository,
    SpectrumSpecializationListRepository,
    TherapistRepository,
    TherapyProfileListRepository,
};
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
        $this->app->bind(AgeListContract::class, AgeListRepository::class);
        $this->app->bind(DegreeListContract::class, DegreeListRepository::class);
        $this->app->bind(ExpertiesListContract::class, ExpertiesListRepository::class);
        $this->app->bind(LanguageListContract::class, LanguageListRepository::class);
        $this->app->bind(TherapyProfileListContract::class, TherapyProfileListRepository::class);
        $this->app->bind(SpectrumSpecializationListContract::class, SpectrumSpecializationListRepository::class);
    }
}

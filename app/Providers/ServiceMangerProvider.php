<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\Classes\{
    AgeListService,
    DegreeListService,
    ExpertiesListService,
    LanguageListService,
    SpectrumSpecializationListService,
    TherapistService,
    TherapyProfileListService,
};
use App\Services\Interfaces\{
    IAgeListService,
    IDegreeListService,
    IExpertiesListService,
    ILanguageListService,
    ISpectrumSpecializationListService,
    ITherapistService,
    ITherapyProfileListService,
};


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
        $this->app->bind(IAgeListService::class, AgeListService::class);
        $this->app->bind(IDegreeListService::class, DegreeListService::class);
        $this->app->bind(IExpertiesListService::class, ExpertiesListService::class);
        $this->app->bind(ILanguageListService::class, LanguageListService::class);
        $this->app->bind(ITherapyProfileListService::class, TherapyProfileListService::class);
        $this->app->bind(ISpectrumSpecializationListService::class, SpectrumSpecializationListService::class);
    }
}

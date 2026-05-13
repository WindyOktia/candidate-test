<?php

namespace App\Providers;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Contracts\Services\ImportExportServiceInterface;
use App\Repositories\LayerRepository;
use App\Repositories\LayupRepository;
use App\Repositories\SupplierRepository;
use App\Services\ImportExportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(LayupRepositoryInterface::class, LayupRepository::class);
        $this->app->bind(LayerRepositoryInterface::class, LayerRepository::class);
        $this->app->bind(ImportExportServiceInterface::class, ImportExportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

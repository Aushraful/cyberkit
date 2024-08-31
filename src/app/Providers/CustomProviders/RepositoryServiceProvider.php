<?php

namespace App\Providers\CustomProviders;

use App\Repositories\Contracts\AuthenticationInterface;
use App\Repositories\Eloquent\AuthenticationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bind AuthenticationRepository to AuthenticationInterface
        $this->app->bind(AuthenticationInterface::class, AuthenticationRepository::class);
    }
}

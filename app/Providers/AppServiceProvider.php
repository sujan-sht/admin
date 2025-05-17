<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Admin\RoleRepository;
use App\Repository\Admin\UserRepository;
use App\Contracts\Admin\RoleRepositoryInterface;
use App\Contracts\Admin\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->repos();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function repos()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        // $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }
}

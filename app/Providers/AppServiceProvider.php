<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );

        /**
         * Blade directive: @canViewMenu('menu-key') ... @endcanViewMenu
         * Contoh: @canViewMenu('barang') <button>Tambah</button> @endcanViewMenu
         */
        Blade::if('canViewMenu', function (string $menuKey) {
            return auth()->check() && auth()->user()->canViewMenu($menuKey);
        });

        /**
         * Blade directive: @canAddMenu('menu-key') ... @endcanAddMenu
         * Contoh: @canAddMenu('barang') <button>Tambah</button> @endcanAddMenu
         */
        Blade::if('canAddMenu', function (string $menuKey) {
            return auth()->check() && auth()->user()->canAddMenu($menuKey);
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\View; // Hapus baris ini
// use App\View\Composers\CurrentRouteComposer; // Hapus baris ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View::composer('*', CurrentRouteComposer::class); // Hapus atau komentar baris ini
    }
}

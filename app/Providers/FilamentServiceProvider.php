<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class FilamentServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
    
    }

   
    public function boot(): void
    {
        Filament::serving(function () {
            // Filament::auth(function () {
            //     return auth()->check(); 
            // });
        });
    }
}


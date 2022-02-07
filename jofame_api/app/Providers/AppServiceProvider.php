<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ripcord\Providers\Laravel\Ripcord;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->register(\Ripcord\Providers\Laravel\ServiceProvider::class);
    }
}

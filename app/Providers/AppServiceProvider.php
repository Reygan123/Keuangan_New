<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        Relation::morphMap([
            'transaksis'                   => \App\Models\Transaksi::class,
            'App\Models\Transaksi'          => \App\Models\Transaksi::class,
            'App\Models\Akun'               => \App\Models\Akun::class,
            'App\Models\JurnalPenyesuaian'  => \App\Models\JurnalPenyesuaian::class,
        ]);
    }
}

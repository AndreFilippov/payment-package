<?php
namespace PaymentModule;
use Illuminate\Support\ServiceProvider;

Class PaymentServiceProvider extends ServiceProvider {
    public function boot(){
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views','payment');
    }

    public function register()
    {

    }
}

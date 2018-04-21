<?php

namespace Therour\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	//
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Cart', function ($app) {
        	return new App\Cart($app->make(App\CartSession::class));
        });
    }

    public function provides()
    {
        return [App\Cart::class];
    }
}

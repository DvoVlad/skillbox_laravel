<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PushallServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(\App\Service\Pushall::class, function() {
			return new \App\Service\Pushall(config("skillbox.pushall.api.key"), config("skillbox.pushall.api.id"));
		});
    }
}

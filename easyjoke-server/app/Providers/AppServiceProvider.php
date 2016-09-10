<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('League\Glide\Server', function($app){

            $filesystem = $app->make('Illuminate\Contracts\Filesystem\Filesystem');
            $factory = $app->make('League\Glide\Responses\LaravelResponseFactory');

            return \League\Glide\ServerFactory::create([
                'response' => $factory,
                'source' => $filesystem->getDriver(),
                'cache' => $filesystem->getDriver(),
                'watermarks' => $filesystem->getDriver(),
                'source_path_prefix' => 'images',
                'cache_path_prefix' => 'images/.cache',
                'watermarks_path_prefix' => 'images/watermarks',
                'base_url' => 'images',
            ]);
        });
    }
}

<?php

namespace SultanulArefin\LogViewer;

use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'log-viewer');

        // Allow users to publish your views to their own project
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/log-viewer'),
            ], 'views');
        }
    }

    public function register()
    {
        // Bind logic into the container if needed
    }
}
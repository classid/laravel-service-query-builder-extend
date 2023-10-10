<?php

namespace Classid\LaravelServiceQueryBuilderExtend;

use Classid\LaravelServiceQueryBuilderExtend\Console\Commands\GenerateQueryCommand;
use Classid\LaravelServiceQueryBuilderExtend\Console\Commands\GenerateServiceCommand;
use Illuminate\Support\ServiceProvider;

class QueryExtendProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/queryextend.php', 'queryextend');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()){
            $this->commands([
                GenerateQueryCommand::class,
                GenerateServiceCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/Config/queryextend.php' => config_path('queryextend.php'),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace CodeStepsBD\HistoryKeeper\Providers;

use App\Models\User;
use CodeStepsBD\HistoryKeeper\Console\Commands\UpdateHistoryTableAndTrigger;
use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class HistoryKeeperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    UpdateHistoryTableAndTrigger::class,
                ],
            );
        }
        Paginator::useBootstrap();
    }

    public function register()
    {

        $this->mergeConfigFrom(__DIR__."/../../config/historyKeeper.php","historyKeeper");

        $routeFilePath = __DIR__."/../../routes/web.php";
        $this->loadRoutesFrom($routeFilePath);

        $viewFilePath = __DIR__."/../../resources/views/";
        $this->loadViewsFrom($viewFilePath,"historyKeeper");

        $this->loadMigrationsFrom(__DIR__."/../../database/migrations");

    }
}

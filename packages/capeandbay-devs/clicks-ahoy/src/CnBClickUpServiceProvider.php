<?php

namespace CapeAndBay\ClicksAhoy;

use CapeAndBay\ClicksAhoy\Services\ClickUpAPIService;
use Illuminate\Support\ServiceProvider;

class CnBClickUpServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/clicks-ahoy.php';

    public function boot()
    {
        $this->loadConfigs();

        $this->publishFiles();

        $this->loadRoutes();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'clicks-ahoy'
        );

        $this->app->bind('ClickUp', function () {
            return new ClickUp(new ClickUpAPIService());
        });
    }

    public function loadConfigs()
    {
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(__DIR__.'/../config/clicks-ahoy.php', 'nautical');
    }

    public function publishFiles()
    {
        $capeandbay_config_files = [__DIR__.'/../config' => config_path()];

        $minimum = array_merge(
            $capeandbay_config_files
        );

        // register all possible publish commands and assign tags to each
        $this->publishes($capeandbay_config_files, 'config');
        $this->publishes($minimum, 'minimum');
    }

    public function loadRoutes()
    {

        $path = '/routes/capeandbay/clickup.php';
        $dir_path = __DIR__.$path;
        if (file_exists(base_path().$path)) {
            $dir_path = base_path().$path;
        }

        $this->loadRoutesFrom($dir_path);
    }
}

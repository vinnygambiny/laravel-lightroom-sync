<?php

namespace VinnyGambiny\LightroomSync;

use Illuminate\Support\ServiceProvider;

class LightroomSyncServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerPublishables();

        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../config/lightroom-sync.php' => config_path('lightroom-sync.php'),
        ], 'config');

        if (! class_exists('AddApiTokenToUserTable')) {
            $this->publishes([
                __DIR__.'/../Database/Migrations/add_api_token_to_user_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_add_api_token_to_user_table.php'),
            ], 'migrations');
        }
    }
}

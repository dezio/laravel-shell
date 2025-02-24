<?php
/**
 * File: ShellServiceProvider.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell;

use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Contracts\ShellFactoryContract;
use DeZio\Shell\Driver\DefaultShellConnection;
use DeZio\Shell\Factory\ShellFactory;
use Illuminate\Support\ServiceProvider;

class ShellServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/shell.php', 'shell');
        $this->publishes([
            __DIR__ . '/../config/shell.php' => config_path('shell.php'),
        ], 'shell-config');
        $this->app->singleton(ShellContainer::class);
        $this->app->bind(ShellConnection::class, DefaultShellConnection::class);
        $this->app->bind(ShellFactoryContract::class, ShellFactory::class);
    }
}

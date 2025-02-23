<?php
/**
 * File: ShellServiceProvider.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell;

use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Driver\DefaultShellConnection;
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
    }
}

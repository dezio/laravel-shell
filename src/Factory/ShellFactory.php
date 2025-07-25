<?php
/**
 * File: ShellLoginFactory.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Factory;

use DeZio\Shell\Authentication\ServerCredentials;
use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Contracts\ShellFactoryContract;
use DeZio\Shell\Exceptions\ConfigurationMissingException;
use DeZio\Shell\Exceptions\LoginException;
use phpseclib3\Net\SSH2;

/**
 * Factory class for creating Shell instances.
 *
 * This class is responsible for creating and configuring Shell objects
 * with the specified parameters and settings.
 *
 * @since 1.0.0
 */
class ShellFactory implements ShellFactoryContract
{
    
    /**
     * Creates a new SSH2 connection using provided server credentials
     * 
     * @param ServerCredentials $credentials The credentials object containing server connection details
     * @return ShellConnection An instance of ShellConnection representing the established SSH2 connection
     * @throws \RuntimeException If the SSH2 connection cannot be established
     */
    public function createShellConnection(ServerCredentials $credentials): ShellConnection
    {
        $ssh = new SSH2($credentials->getHost(), $credentials->getPort(), 0);
        if (!$ssh->login($credentials->getLogin()->getUsername(), $credentials->getLogin()->getPassword())) {
            throw new LoginException($credentials);
        } // if end

        $config = config('shell');
        $defaultShell = $config['default_shell'] ?? null;
        throw_unless($defaultShell, new ConfigurationMissingException('Default shell not set in config file'));

        return app()->make($defaultShell, [
            'ssh'         => $ssh,
            'credentials' => $credentials,
            'config'      => $config,
        ]);
    }
}

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
use Exception;
use phpseclib3\Net\SSH2;

class ShellFactory implements ShellFactoryContract
{
    /**
     * Create a new SSH2 connection
     *
     * @param ServerCredentials $credentials
     * @return ShellConnection
     * @throws Exception
     */
    public function createShellConnection(ServerCredentials $credentials): ShellConnection
    {
        $ssh = new SSH2($credentials->getHost(), $credentials->getPort());
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

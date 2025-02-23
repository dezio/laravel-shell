<?php
/**
 * File: ShellLoginFactory.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Factory;

use DeZio\Shell\Authentication\ServerCredentials;
use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Driver\DefaultShellConnection;
use DeZio\Shell\Exceptions\LoginException;
use Exception;
use phpseclib3\Net\SSH2;

class ShellFactory
{
    /**
     * Create a new SSH2 connection
     *
     * @param ServerCredentials $credentials
     * @return ShellConnection
     * @throws Exception
     */
    public function createSSH2Connection(ServerCredentials $credentials): ShellConnection
    {
        $ssh = new SSH2($credentials->getHost(), $credentials->getPort());
        if (!$ssh->login($credentials->getLogin()->getUsername(), $credentials->getLogin()->getPassword())) {
            throw new LoginException($credentials);
        } // if end

        return new DefaultShellConnection($ssh, $credentials);
    }
}

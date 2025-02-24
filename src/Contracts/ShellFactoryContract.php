<?php
/**
 * File: ShellFactory.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

use DeZio\Shell\Authentication\ServerCredentials;
use Exception;

interface ShellFactoryContract
{
    /**
     * Create a new SSH2 connection
     *
     * @param ServerCredentials $credentials
     * @return ShellConnection
     * @throws Exception
     */
    public function createShellConnection(ServerCredentials $credentials): ShellConnection;
}

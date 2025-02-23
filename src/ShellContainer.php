<?php
/**
 * File: ShellContainer.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell;

use DeZio\Shell\Contracts\HasServerCredentials;
use DeZio\Shell\Factory\ShellFactory;
use Log;

class ShellContainer
{
    private array $connections;
    private ShellFactory $loginFactory;

    public function __construct()
    {
        $this->connections = [];
        $this->loginFactory = new ShellFactory();
    }

    /**
     *
     */
    public function addConnection(HasServerCredentials $connection)
    {
        $loginId = $connection->getServerCredentials()->getId();
        if (isset($this->connections[$loginId])) {
            return $this->connections[$loginId];
        }

        return $this->connections[$loginId] = $this->createConnection($connection);
    }

    /**
     *
     */
    private function createConnection(HasServerCredentials $connection): Contracts\ShellConnection
    {
        $loginId = $connection->getServerCredentials()->getId();

        Log::info("Creating connection", [
            'loginId' => $loginId,
            'server'  => $connection->getServerCredentials()->getHost(),
        ]);
        return $this->loginFactory->createSSH2Connection($connection->getServerCredentials());
    }
}

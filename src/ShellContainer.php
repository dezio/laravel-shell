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
    private array $config;

    public function __construct()
    {
        $this->connections = [];
        $this->loginFactory = new ShellFactory();
        $this->config = config('shell');
    }

    /**
     *
     */
    public function addConnection(HasServerCredentials $connection)
    {
        if ($this->config['logging']) {
            Log::info("Adding connection", [
                'loginId' => $connection->getServerCredentials()->getId(),
                'server'  => $connection->getServerCredentials()->getHost(),
            ]);
        } // if end

        $loginId = $connection->getServerCredentials()->getId();
        if (isset($this->connections[$loginId])) {
            return $this->connections[$loginId];
        } // if end

        return $this->connections[$loginId] = $this->createConnection($connection);
    }

    public function __destruct()
    {
        foreach ($this->connections as $connection) {
            unset($connection);
        } // foreach end
    }

    /**
     *
     */
    private function createConnection(HasServerCredentials $connection): Contracts\ShellConnection
    {
        $loginId = $connection->getServerCredentials()->getId();
        if ($this->config['logging']) {
            Log::info("Creating connection", [
                'loginId' => $loginId,
                'server'  => $connection->getServerCredentials()->getHost(),
            ]);
        } // if end

        return $this->loginFactory->createSSH2Connection($connection->getServerCredentials());
    }
}

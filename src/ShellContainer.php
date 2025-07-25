<?php
/**
 * File: ShellContainer.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell;

use Context;
use DeZio\Shell\Contracts\HasServerCredentials;
use DeZio\Shell\Contracts\ShellFactoryContract;
use DeZio\Shell\Events\ShellConnected;
use Exception;
use Log;

/**
 * Manages SSH connections and provides an interface for adding and creating them.
 *
 * Handles connections creation using a configuration file. Every connection is
 * stored in the internal connections array for future reuse. Also utilizes a
 * factory for instantiating shell connections and supports logging if enabled.
 */
class ShellContainer
{
    private array $connections;
    private ShellFactoryContract $loginFactory;
    private array $config;

    /**
     *
     */
    public function __construct()
    {
        $this->connections = [];
        $this->loginFactory = app(ShellFactoryContract::class);
        $this->config = config('shell');
    }

    /**
     * Adds a new SSH connection using the provided server credentials.
     *
     * Logs the addition of the connection if logging is enabled in the configuration.
     * The server credentials are stored in the application context under the 'ssh' key.
     * If a connection for the given login ID already exists, the existing connection is returned.
     * Otherwise, a new connection is created and stored.
     *
     * @param HasServerCredentials $connection The connection instance containing server credentials.
     * @return Contracts\ShellConnection The created or existing shell connection.
     */
    public function addConnection(HasServerCredentials $connection)
    {
        $credentials = $connection->getServerCredentials();
        if ($this->config['logging']) {
            Log::info("Adding connection", [
                'loginId' => $credentials->getId(),
                'server'  => $credentials->getHost(),
            ]);
        } // if end

        Context::add('ssh', $credentials->toArray());
        $loginId = $credentials->getId();

        return $this->connections[$loginId] ?? ($this->connections[$loginId] = $this->createConnection($connection));
    }

    public function forceReconnect(HasServerCredentials $connection): Contracts\ShellConnection
    {
        $credentials = $connection->getServerCredentials();
        unset($this->connections[$credentials->getId()]);

        return $this->addConnection($connection);
    }

    /**
     *
     */
    public function __destruct()
    {
        foreach ($this->connections as $connection) {
            unset($connection);
        } // foreach end
    }

    /**
     * Establishes a connection based on the provided server credentials.
     *
     * @param HasServerCredentials $credentials The object providing server credentials.
     *
     * @return Contracts\ShellConnection The created shell connection.
     * @throws Exception
     */
    private function createConnection(HasServerCredentials $credentials): Contracts\ShellConnection
    {
        $loginId = $credentials->getServerCredentials()->getId();
        if ($this->config['logging']) {
            Log::info("Creating connection", [
                'loginId' => $loginId,
                'server'  => $credentials->getServerCredentials()->getHost(),
            ]);
        } // if end

        $connection = $this->loginFactory->createShellConnection($credentials->getServerCredentials());

        event(new ShellConnected($credentials, $connection));

        return $connection;
    }
}

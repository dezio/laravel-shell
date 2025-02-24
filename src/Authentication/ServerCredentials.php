<?php
/**
 * File: ServerCredentials.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Authentication;

use Illuminate\Contracts\Support\Arrayable;

class ServerCredentials implements Arrayable
{
    private Login $login;
    private string $host;
    private int $port;

    /**
     * ServerCredentials constructor.
     *
     * @param Login $login The login instance containing user credentials.
     * @param string $host The server host address.
     * @param int $port The server port number.
     */
    public function __construct(Login $login, string $host, int $port)
    {
        $this->login = $login;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Retrieve the login credentials.
     *
     * @return Login The login credentials.
     */
    public function getLogin(): Login
    {
        return $this->login;
    }

    /**
     * Get the host of the server.
     *
     * @return string The host of the server.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the port number for the server credentials.
     *
     * @return int The port number.
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Get the ID of the server credentials.
     *
     * @return mixed The ID of the server credentials. Format: username@host:port
     */
    public function getId()
    {
        return sprintf("%s@%s:%d", $this->login->getUsername(), $this->host, $this->port);
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'login' => $this->login->getUsername(),
            'host' => $this->host,
            'port' => $this->port,
        ];
    }
}

<?php
/**
 * File: ServerCredentials.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Authentication;

class ServerCredentials
{
    private Login $login;
    private string $host;
    private int $port;

    public function __construct(Login $login, string $host, int $port)
    {
        $this->login = $login;
        $this->host = $host;
        $this->port = $port;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}

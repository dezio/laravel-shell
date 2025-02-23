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

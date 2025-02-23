<?php
/**
 * File: DynamicHost.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Dynamic;

use DeZio\Shell\Authentication\Login;
use DeZio\Shell\Authentication\ServerCredentials;
use DeZio\Shell\Contracts\HasServerCredentials;
use phpseclib3\Crypt\RSA;

class DynamicServer implements HasServerCredentials
{
    private string $host;
    private int $port;
    private string $username;
    private mixed $password;

    public function __construct(string $host, int $port, string $username, mixed $password = "")
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function withPrivateKeyPath(string $path, string $passphrase): static
    {
        $privateKey = file_get_contents($path);
        $this->password = RSA::loadPrivateKey($privateKey, $passphrase);

        return $this;
    }


    public function getLoginId(): string
    {
        return $this->host . ':' . $this->port;
    }

    public function getServerCredentials(): ServerCredentials
    {
        $login = new Login($this->username, $this->password);
        return new ServerCredentials($login, $this->host, $this->port);
    }
}

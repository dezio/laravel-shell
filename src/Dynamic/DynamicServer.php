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

/**
 * Class DynamicServer
 *
 * Provides dynamic server functionality and manages server credentials.
 * Implements the HasServerCredentials interface to standardize credential retrieval
 * and management across various server types.
 *
 */
class DynamicServer implements HasServerCredentials
{
    private string $host;
    private int $port;
    private string $username;
    private mixed $password;

    /**
     * Constructs a new instance of the DynamicServer.
     *
     * @param string $host The host name or IP address for the connection.
     * @param int $port The port number for the connection.
     * @param string $username The username for authentication.
     * @param mixed $password The password for authentication. Defaults to an empty string if not provided.
     */
    public function __construct(string $host, int $port, string $username, mixed $password = "")
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Sets the private key path and its passphrase for authentication.
     *
     * @param string $path The filesystem path to the private key.
     * @param string $passphrase The passphrase for the private key.
     * @return static Returns the instance of the class for method chaining.
     */
    public function withPrivateKeyPath(string $path, string $passphrase): static
    {
        $privateKey = file_get_contents($path);
        $this->password = RSA::loadPrivateKey($privateKey, $passphrase);

        return $this;
    }

    /**
     * Retrieves the server credentials required for secure connections.
     *
     * @return ServerCredentials Returns an instance containing the server credentials.
     */
    public function getServerCredentials(): ServerCredentials
    {
        $login = new Login($this->username, $this->password);
        return new ServerCredentials($login, $this->host, $this->port);
    }
}

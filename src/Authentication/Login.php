<?php
/**
 * File: Login.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Authentication;

use phpseclib3\Crypt\RSA;

class Login
{
    private string $username;
    private string|RSA $password;

    public function __construct(string $username, string|RSA $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the username.
     *
     * @return string The username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Retrieve the password.
     *
     * @return string|RSA The password.
     */
    public function getPassword(): mixed
    {
        return $this->password;
    }
}

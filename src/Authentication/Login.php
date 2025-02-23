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
    private mixed $password;

    public function __construct(string $username, mixed $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): mixed
    {
        return $this->password;
    }
}

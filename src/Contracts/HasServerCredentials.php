<?php
/**
 * File: ShellAuthenticatable.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

use DeZio\Shell\Authentication\Login;
use DeZio\Shell\Authentication\ServerCredentials;

interface HasServerCredentials
{
    /**
     * Retrieves the login identifier. This is used to cache and reuse the connection.
     *
     * @return string The login ID.
     */
    public function getLoginId(): string;

    /**
     * Retrieves the server credentials.
     */
    public function getServerCredentials(): ServerCredentials;
}

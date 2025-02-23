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
     * Retrieves the server credentials.
     */
    public function getServerCredentials(): ServerCredentials;
}

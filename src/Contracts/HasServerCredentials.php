<?php
/**
 * File: ShellAuthenticatable.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

use DeZio\Shell\Authentication\Login;
use DeZio\Shell\Authentication\ServerCredentials;

/**
 * Interface HasServerCredentials
 *
 * This interface defines the contract for classes that provide server credentials.
 *
 * @package App\Contracts
 */
interface HasServerCredentials
{
    /**
     * Retrieves the server credentials.
     */
    public function getServerCredentials(): ServerCredentials;
}

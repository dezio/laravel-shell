<?php
/**
 * File: LoginException.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Exceptions;

use DeZio\Shell\Authentication\ServerCredentials;

/**
 * Represents an exception thrown when the user login process fails.
 * 
 * Use this exception to indicate that the login requirements were not met,
 * such as invalid credentials or user access issues.
 * 
 * @package laravel-shell
 */
class LoginException extends \Exception
{
    private ServerCredentials $credentials;

    /**
     * Instantiates a new LoginException instance with the specified credentials.
     *
     * @param ServerCredentials $credentials Server connection credentials instance.
     * @param string            $message     Optional error message.
     * @param int               $code        Optional error code.
     * @param \Throwable|null   $previous    Optional previous exception for chaining.
     */
    public function __construct(ServerCredentials $credentials, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->credentials = $credentials;
        if (empty($message)) {
            $message = "Login failed for " . $credentials->getHost() . ":" . $credentials->getPort() . " with username " . $credentials->getLogin()->getUsername();
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Retrieve the server credentials associated with the login exception.
     * 
     * @return ServerCredentials The credentials object containing server authentication details
     */
    public function getCredentials(): ServerCredentials
    {
        return $this->credentials;
    }
}

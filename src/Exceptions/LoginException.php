<?php
/**
 * File: LoginException.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Exceptions;

use DeZio\Shell\Authentication\ServerCredentials;

class LoginException extends \Exception
{
    private ServerCredentials $credentials;

    public function __construct(ServerCredentials $credentials, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->credentials = $credentials;
        if (empty($message)) {
            $message = "Login failed for " . $credentials->getHost() . ":" . $credentials->getPort() . " with username " . $credentials->getLogin()->getUsername();
        }

        parent::__construct($message, $code, $previous);
    }

    public function getCredentials(): ServerCredentials
    {
        return $this->credentials;
    }
}

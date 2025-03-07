<?php
/**
 * File: LoginException.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Exceptions;

use DeZio\Shell\Authentication\ServerCredentials;

/**
 * Exception thrown for command-related errors.
 *
 * This exception indicates that an error has occurred while
 * running or processing a shell command within the application.
 *
 */
class CommandException extends \Exception
{
    private string $command;

    public function __construct(string $command, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->command = $command;
        if (empty($message)) {
            $message = "Command failed: " . str($command)->limit(100);
        }

        parent::__construct($message, $code, $previous);
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}

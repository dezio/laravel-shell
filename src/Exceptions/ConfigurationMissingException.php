<?php
/**
 * File: ConfigurationMissing.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Exceptions;

class ConfigurationMissingException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace DeZio\Shell\Configuration;

class ShellConfig
{
    public bool $logging;
    public bool $trimOutput;
    public int $timeout;
    public bool $throwError;
    public int $throwErrorCounter;
    public string $defaultShell;
    public string $decodeCommands;

    // Private constructor to enforce instantiation via fromArray
    private function __construct() {}

    public static function fromArray(array $config): self
    {
        $instance = new self();
        $defaults = [
            'logging'             => true,
            'trim_output'         => true,
            'timeout'             => 10,
            'throw_error'         => false,
            'throw_error_counter' => -1,
            'default_shell'       => \DeZio\Shell\Driver\DefaultShellConnection::class,
            'decode_commands'     => \DeZio\Shell\Driver\Encoder\Base64Encoder::class,
        ];

        $data = array_merge($defaults, $config);

        $instance->logging = (bool)$data['logging'];
        $instance->trimOutput = (bool)$data['trim_output'];
        $instance->timeout = (int)$data['timeout'];
        $instance->throwError = (bool)$data['throw_error'];
        $instance->throwErrorCounter = (int)$data['throw_error_counter'];
        $instance->defaultShell = (string)$data['default_shell'];
        $instance->decodeCommands = (string)$data['decode_commands'];

        return $instance;
    }

    public function isLoggingEnabled(): bool
    {
        return $this->logging;
    }

    public function isTrimOutput(): bool
    {
        return $this->trimOutput;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function isThrowError(): bool
    {
        return $this->throwError;
    }

    public function getThrowErrorCounter(): int
    {
        return $this->throwErrorCounter;
    }

    public function decrementThrowErrorCounter(): void
    {
        if ($this->throwErrorCounter > 0) {
            $this->throwErrorCounter--;
        }
        if ($this->throwErrorCounter === 0) {
            $this->throwErrorCounter = -1;
            $this->throwError = false;
        }
    }

    public function getDefaultShell(): string
    {
        return $this->defaultShell;
    }

    public function getDecodeCommands(): string
    {
        return $this->decodeCommands;
    }
}

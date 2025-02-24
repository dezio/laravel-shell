<?php

namespace DeZio\Shell\Configuration;

/**
 * Class ShellConfig
 *
 * Handles shell configuration settings.
 */
class ShellConfig
{
    /**
     * Enable logging.
     *
     * @var bool
     */
    public bool $logging;

    /**
     * Trim output.
     *
     * @var bool
     */
    public bool $trimOutput;

    /**
     * Command execution timeout.
     *
     * @var int
     */
    public int $timeout;

    /**
     * Flag to throw error.
     *
     * @var bool
     */
    public bool $throwError;

    /**
     * Counter for error throwing.
     *
     * @var int
     */
    public int $throwErrorCounter;

    /**
     * Default shell class.
     *
     * @var string
     */
    public string $defaultShell;

    /**
     * Command decoder class.
     *
     * @var string
     */
    public string $decodeCommands;

    /**
     * Private constructor to enforce instantiation via fromArray.
     */
    private function __construct() {}

    /**
     * Instantiate ShellConfig from an array.
     *
     * @param array $config Configuration array.
     * @return self
     */
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

    /**
     * Check if logging is enabled.
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return $this->logging;
    }

    /**
     * Check if output should be trimmed.
     *
     * @return bool
     */
    public function isTrimOutput(): bool
    {
        return $this->trimOutput;
    }

    /**
     * Retrieve the command execution timeout.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Determine if error throwing is enabled.
     *
     * @return bool
     */
    public function isThrowError(): bool
    {
        return $this->throwError;
    }

    /**
     * Get the error throw counter.
     *
     * @return int
     */
    public function getThrowErrorCounter(): int
    {
        return $this->throwErrorCounter;
    }

    /**
     * Decrement the error throw counter.
     *
     * @return void
     */
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

    /**
     * Get the default shell.
     *
     * @return string
     */
    public function getDefaultShell(): string
    {
        return $this->defaultShell;
    }

    /**
     * Get the decoder command class.
     *
     * @return string
     */
    public function getDecodeCommands(): string
    {
        return $this->decodeCommands;
    }
}

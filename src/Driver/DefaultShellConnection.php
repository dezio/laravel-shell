<?php
/**
 * File: DefaultShellConnection.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Driver;

use DeZio\Shell\Authentication\ServerCredentials;
use DeZio\Shell\Contracts\CommandEncoder;
use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Contracts\ShellFileSystem;
use DeZio\Shell\Contracts\ShellResponse;
use DeZio\Shell\Driver\Encoder\Base64Encoder;
use DeZio\Shell\Exceptions\CommandException;
use DeZio\Shell\Response\DefaultShellResponse;
use Log;
use phpseclib3\Net\SSH2;

class DefaultShellConnection implements ShellConnection
{
    private SSH2 $ssh;
    private ServerCredentials $credentials;
    private CommandEncoder $encoder;

    private array $config = [
        'logging'    => true,
        'trim_output' => true,
        'timeout'    => 10,
        'throwError' => false,
        'throwErrorCounter' => -1
    ];

    public function withThrowError(bool $throwError, int $maxTimes = -1): ShellConnection
    {
        $newConfig = $this->config;
        $newConfig['throw_error'] = $throwError;
        $newConfig['throw_error_counter'] = $maxTimes;

        return new self($this->ssh, $this->credentials, $newConfig);
    }

    public function __construct(SSH2 $ssh, ServerCredentials $credentials, array $config = [])
    {
        $this->ssh = $ssh;
        $this->credentials = $credentials;
        $this->config = array_merge($this->config, $config);
        $this->encoder = app()->make($this->config['decode_commands']);
    }

    protected function log(string $level, string $message, array $meta = []): void
    {
        if ($this->isLoggingEnabled()) {
            Log::channel('shell')->$level($message, $meta);
        }
    }

    /**
     * Executes a shell command through SSH and returns the response.
     *
     * This method composes and executes the given shell command using
     * the SSH connection. It manages the command's timeout, error
     * handling, logging, and response formatting.
     *
     * @param array $args The array of arguments composing the shell command.
     * @return ShellResponse The response containing the command output,
     *                       error details, and exit code.
     * @throws CommandException If the command fails and error throwing is enabled.
     */
    public function exec(array $args): ShellResponse
    {
        $command = implode(' ', $args);
        $this->ssh->setTimeout($this->getTimeout());

        $command = $this->encoder->encode($command);
        $output = $this->ssh->exec($command);
        $error = $this->ssh->getLastError();
        $exitCode = $this->ssh->getExitStatus();

        $this->decrementThrowErrorCounter();
        if ($exitCode !== 0 && $this->isThrowError()) {
            $this->log('error', "Command failed: $command", [
                'output'    => $output,
                'error'     => $error,
                'exit_code' => $exitCode
            ]);
            throw new CommandException("Command failed: $command");
        }

        $this->log('info', "Command executed", [
            'command'   => $command,
            'output'    => str($output)->limit()->trim()->replace("\n", " ")->value(),
            'error'     => strlen($error),
            'exit_code' => $exitCode
        ]);

        $response = new DefaultShellResponse($output, $error, $exitCode);
        $response->trimOutput($this->isTrimOutput());
        return $response;
    }

    public function isLoggingEnabled(): bool
    {
        return $this->config['logging'];
    }

    public function isTrimOutput(): bool
    {
        return $this->config['trim_output'];
    }

    public function getTimeout(): int
    {
        return $this->config['timeout'];
    }

    public function isThrowError(): bool
    {
        return $this->config['throw_error'];
    }

    public function io(): ShellFileSystem
    {
        return new DefaultFileDriver($this);
    }

    public function getCredentials(): ServerCredentials
    {
        return $this->credentials;
    }

    public function execSimple(string $command): ShellResponse
    {
        return $this->exec(explode(' ', $command));
    }

    public function json(array $args): array
    {
        $response = $this->exec($args);
        return json_decode((string)$response->getOutput(), true);
    }

    /**
     * @return void
     */
    private function decrementThrowErrorCounter(): void
    {
        if ($this->config['throwErrorCounter'] > 0) {
            $this->config['throwErrorCounter']--;
        }

        if ($this->config['throwErrorCounter'] === 0) {
            $this->config['throwErrorCounter'] = -1;
            $this->config['throwError'] = false;
        }
    }

    public function close(): void
    {
        $this->ssh->disconnect();
    }
}

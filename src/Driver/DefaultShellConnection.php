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
use DeZio\Shell\Events\AfterShellExecute;
use DeZio\Shell\Events\BeforeShellExecute;
use DeZio\Shell\Exceptions\CommandException;
use DeZio\Shell\Response\DefaultShellResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use JsonException;
use Log;
use phpseclib3\Net\SSH2;

/**
 *
 */
class DefaultShellConnection implements ShellConnection
{
    /**
     * @var SSH2 $ssh The SSH2 connection instance.
     */
    private SSH2 $ssh;

    /**
     * @var ServerCredentials $credentials The server credentials instance.
     */
    private ServerCredentials $credentials;

    /**
     * @var CommandEncoder $encoder The command encoder instance.
     */
    private CommandEncoder $encoder;

    private array $config = [
        'logging'    => true,
        'trim_output' => true,
        'timeout'    => 10,
        'throwError' => false,
        'throwErrorCounter' => -1
    ];

    /**
     * Create a new SSH2 connection instance.
     *
     * @param SSH2 $ssh
     * @param ServerCredentials $credentials
     * @param array $config
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct(SSH2 $ssh, ServerCredentials $credentials, array $config = [])
    {
        $this->ssh = $ssh;
        $this->credentials = $credentials;
        $this->config = array_merge($this->config, $config);
        $this->encoder = app()->make($this->config['decode_commands']);
    }

    /**
     * Configure the instance to throw an error with an optional maximum number of occurrences.
     *
     * @param bool $throwError Whether to enable or disable throwing errors.
     * @param int $maxTimes The maximum number of times errors should be thrown (-1 for unlimited).
     * @return ShellConnection
     * @throws BindingResolutionException
     */
    public function withThrowError(bool $throwError, int $maxTimes = -1): ShellConnection
    {
        $newConfig = $this->config;
        $newConfig['throw_error'] = $throwError;
        $newConfig['throw_error_counter'] = $maxTimes;

        return new self($this->ssh, $this->credentials, $newConfig);
    }

    public function io(): ShellFileSystem
    {
        return new DefaultFileDriver($this);
    }

    /**
     * Retrieves the server credentials.
     *
     * @return ServerCredentials
     */
    public function getCredentials(): ServerCredentials
    {
        return $this->credentials;
    }

    /**
     * Executes a simple shell command by splitting it into arguments and delegating it to the exec method.
     *
     * @param string $command The shell command to be executed.
     * @return ShellResponse
     * @throws CommandException
     */
    public function execSimple(string $command): ShellResponse
    {
        return $this->exec(explode(' ', $command));
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

        event(new BeforeShellExecute($command));
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
        event(new AfterShellExecute($response));
        $response->trimOutput($this->isTrimOutput());
        return $response;
    }

    /**
     * Determines the timeout for the command execution based on the configuration.
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->config['timeout'];
    }

    /**
     * Decrements the error counter if it is greater than zero.
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

    /**
     * Determines if errors should be thrown based on the configuration.
     * @return bool
     */
    public function isThrowError(): bool
    {
        return $this->config['throw_error'];
    }

    /**
     * Logs a message with the specified level and metadata if logging is enabled.
     *
     * @param string $level The severity level of the log message.
     * @param string $message The log message to be recorded.
     * @param array $meta Additional metadata to include with the log message.
     *
     * @return void
     */
    protected function log(string $level, string $message, array $meta = []): void
    {
        if ($this->isLoggingEnabled()) {
            Log::channel('shell')->$level($message, $meta);
        }
    }

    /**
     * Determines if logging is enabled based on the configuration.
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return $this->config['logging'];
    }

    /**
     * Determines if the command output should be trimmed based on the configuration.
     *
     * @return bool
     */
    public function isTrimOutput(): bool
    {
        return $this->config['trim_output'];
    }

    /**
     * Executes a shell command and returns the output as a JSON-decoded associative array.
     *
     * @param array $args The arguments to be passed to the command.
     * @return array The decoded JSON response as an associative array.
     * @throws CommandException
     * @throws JsonException
     */
    public function json(array $args): array
    {
        $response = $this->exec($args);
        return json_decode((string)$response->getOutput(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Closes the SSH connection by disconnecting from it.
     */
    public function close(): void
    {
        $this->ssh->disconnect();
    }
}

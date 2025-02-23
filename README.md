# Shell Manager for Laravel

This package provides a simple way to communicate with servers via ssh.

# Installation

```bash
composer require dezio/laravel-shell
```

# Usage

```php
use Dezio\LaravelShell\Shell;use DeZio\Shell\Authentication\Login;use DeZio\Shell\Authentication\ServerCredentials;use DeZio\Shell\Contracts\HasServerCredentials;use DeZio\Shell\Facades\SSH;

class Server implements HasServerCredentials {
    public function getLoginId() {
        return __CLAS__ . "::" . $this->id;
    }
    
    public function getServerCredentials(): ServerCredentials {
        $login = new Login($this->username, $this->password);
        return new ServerCredentials($login, $this->ip, $this->port);
    }
}

$srv = new Server();

// New connection with one time error throwing
$ssh = SSH::addConnection($srv)->withThrowError(true, 1);
$output = $ssh->exec(['ls', '-la']);

echo "Output: " . $output->getOutput();
echo "Error: " . $output->getError();
echo "Exit code: " . $output->getExitCode();
```

## Core concept

### ShellConnection

```php
<?php
interface ShellConnection
{
    /**
    * Retrieves the server credentials.
    *
    * @return ServerCredentials
    */
    public function getCredentials(): ServerCredentials;

    /**
     * Execute the provided arguments and return the shell response.
     *
     * @param array $args Array of arguments to be executed.
     * @return ShellResponse The response from the shell execution.
     */
    public function exec(array $args): ShellResponse;

    /**
     * Execute a simple shell command and return the shell response.
     *
     * @param string $command The shell command to be executed.
     * @return ShellResponse The response from the shell execution.
     */
    public function execSimple(string $command): ShellResponse;

    /**
     * Process the provided arguments and return a JSON-encoded array.
     *
     * @param array $args Array of arguments to be processed.
     * @return array The JSON-encoded representation of the processed arguments.
     */
    public function json(array $args): array;

    public function io(): ShellFileSystem;

    /**
     * Enable or disable logging for the shell connection.
     *
     * @param bool $logging Determines whether logging is enabled.
     * @return ShellConnection
     */
    public function withLogging(bool $logging): ShellConnection;

    /**
     * Sets the trim output option for the shell connection.
     *
     * @param bool $trimOutput Indicates whether output should be trimmed.
     * @return ShellConnection
     */
    public function withTrimOutput(bool $trimOutput): ShellConnection;

    /**
     * Sets the timeout duration for the shell connection.
     *
     * @param int $timeout The timeout duration in seconds.
     * @return ShellConnection
     */
    public function withTimeout(int $timeout): ShellConnection;

    /**
     * Sets the throw error option for the shell connection.
     *
     * @param bool $throwError Specifies whether an error should be thrown during execution.
     * @param int $maxTimes The maximum number of times an error can occur (-1 for unlimited).
     * @return ShellConnection
     */
    public function withThrowError(bool $throwError, int $maxTimes = -1): ShellConnection;

    /**
     * Closes the current connection or operation.
     *
     * @return void
     */
    public function close(): void;
}
```

## Exceptions

The library can throw the following exceptions:
- LoginException, thrown when the login fails.
- CommandException, thrown when the command execution fails and throwing errors is enabled.

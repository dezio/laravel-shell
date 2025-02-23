# Shell Manager for Laravel
This package provides a simple way to communicate with servers via ssh. It is based on the phpseclib library
It is a wrapper around the phpseclib library, which provides a simple way to communicate with servers via ssh.
You can execute commands, process output and make I/O operations using this package.

# Installation

```bash
composer require dezio/laravel-shell
```

# Usage

```php
use DeZio\Shell\Authentication\ServerCredentials;use DeZio\Shell\Contracts\HasServerCredentials;use DeZio\Shell\Facades\SSH;

class Server implements HasServerCredentials {

    /**
    * Returns a server credentials object.
    * @return ServerCredentials
    */
    public function getServerCredentials(): ServerCredentials {
        $login = new Login($this->username, $this->password);
        return new ServerCredentials($login, $this->ip_address, $this->port);
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
     * Process the provided arguments and return a JSON-encoded array.
     *
     * @param array $args Array of arguments to be processed.
     * @return array The JSON-encoded representation of the processed arguments.
     */
    public function json(array $args): array;
}
```

## Exceptions

The library can throw the following exceptions:
- LoginException, thrown when the login fails.
- CommandException, thrown when the command execution fails and throwing errors is enabled.

## Configuration

The package provides a configuration file that can be published using the following command:

```bash
php artisan vendor:publish --provider="DeZio\Shell\ShellServiceProvider" --tag="config"
```

The configuration file is located at `config/shell.php` and contains the following options:

```php
return [
    // The timeout for the shell connection
    'timeout' => 10,
    
    // Determines whether errors should be thrown
    'throw_error' => false,
    
    // The default shell connection class
    'default_shell' => \DeZio\Shell\Driver\DefaultShellConnection::class,
    
    // The default command encoding method
    'decode_commands' => 'base64'
];
```

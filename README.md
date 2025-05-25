# Laravel Shell

A powerful Laravel package for executing SSH commands and managing remote servers directly from your Laravel application.

## Description

Laravel Shell allows you to transform Laravel models into SSH-capable objects, enabling seamless interaction with remote servers. It provides a clean, fluent API for executing commands, managing files, and retrieving system information from remote servers.

This package uses [phpseclib/phpseclib](https://github.com/phpseclib/phpseclib) under the hood for secure SSH connections.

## Requirements

- PHP 8.0 or higher
- Laravel 11.0 or higher
- phpseclib/phpseclib 3.0 or higher

## Installation

Install via Composer:

```bash
composer require dezio/laravel-shell
```

The package will automatically register its service provider if you're using Laravel's package auto-discovery.

Publish and customize the configuration with:

```bash
php artisan vendor:publish --provider="DeZio\Shell\ShellServiceProvider"
```

## Usage

### Basic Usage

The package provides a simple API to:

- Establish SSH connections to remote servers
- Execute commands and retrieve their output
- Manage files on remote servers (create, write, delete)
- Transform Laravel models into SSH-capable objects

Here's a basic example:

```php
<?php

use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

// Create a server connection
$server = new DynamicServer('192.168.1.2', 22, 'root', 'password');
$shell = SSH::addConnection($server);

// Execute a command
$response = $shell->exec(['hostname']);

// Get the command output
echo $response->getOutput();
```

### Response Handling

The returned response object (of type `ShellResponse`) provides several methods:

- `getOutput()`: Returns the standard output from the command
- `getError()`: Returns any error messages or standard error output
- `getExitCode()`: Returns the exit code (0 usually indicates success)
- `isSuccess()`: Boolean flag that indicates whether the command executed successfully

### File Operations

You can perform file operations on the remote server:

```php
// Write a file
$shell->io()->writeFile('/tmp/hello.txt', 'Hello World!');

// Read a file
$content = $shell->io()->readFile('/tmp/hello.txt');

// Delete a file
$shell->io()->deleteFile('/tmp/hello.txt');
```

### SSH Key Authentication

If you prefer key-based authentication, use your SSH private key:

```php
use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

$server = new DynamicServer('192.168.1.2', 22, 'root', null);
$server->setPrivateKey('/path/to/private/key.pem');
$shell = SSH::addConnection($server);
$response = $shell->exec(['hostname']);
echo $response->getOutput();
```

### Using Laravel Models for SSH Access

Any Laravel model can be used for managing SSH credentials by implementing the `HasServerCredentials` interface:

```php
use Illuminate\Database\Eloquent\Model;
use DeZio\Shell\Contracts\HasServerCredentials;
use DeZio\Shell\Authentication\Login;
use DeZio\Shell\Authentication\ServerCredentials;

class Server extends Model implements HasServerCredentials
{
    // Assuming the model has host, port, username and password_or_key properties
    public function getServerCredentials(): ServerCredentials
    {
        $login = new Login($this->username, $this->password_or_key);
        return new ServerCredentials($login, $this->host, $this->port);
    }
}
```

Then use it with the SSH facade:

```php
$server = Server::find(1);
$shell = SSH::addConnection($server);
```

## System Information Examples

### Parsing Current Load Average

```php
use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

$server = new DynamicServer('192.168.1.2', 22, 'root', 'password');
$shell = SSH::addConnection($server);

$response = $shell->exec(['cat', '/proc/loadavg']);
$output = trim($response->getOutput());
// Expected output format: "0.21 0.35 0.44 1/234 5678"
$parts = explode(' ', $output);
if(count($parts) >= 3) {
    echo "Load averages: 1min {$parts[0]}, 5min {$parts[1]}, 15min {$parts[2]}\n";
}
```

### Getting Memory Usage

```php
use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

$server = new DynamicServer('192.168.1.2', 22, 'root', 'password');
$shell = SSH::addConnection($server);

$response = $shell->exec(['cat', '/proc/meminfo']);
$output = $response->getOutput();
$lines = explode("\n", $output);
$memTotal = $memAvailable = null;
foreach ($lines as $line) {
    if (strpos($line, 'MemTotal:') === 0) {
        $parts = preg_split('/\s+/', $line);
        $memTotal = $parts[1] ?? null;
    }
    if (strpos($line, 'MemAvailable:') === 0) {
        $parts = preg_split('/\s+/', $line);
        $memAvailable = $parts[1] ?? null;
    }
}
if ($memTotal !== null && $memAvailable !== null) {
    echo "Memory Usage: Total {$memTotal} kB, Available {$memAvailable} kB\n";
}
```

## Configuration

The configuration file located at `config/shell.php` defines various options:

```php
<?php
return [
    // Whether logging is enabled
    'logging'         => true,

    // Whether to trim the output
    'trimOutput'      => true,

    // The timeout for the shell connection (in seconds)
    'timeout'         => 10,

    // Determines whether errors should be thrown
    'throw_error'     => false,

    // The default shell connection class
    'default_shell'   => \DeZio\Shell\Driver\DefaultShellConnection::class,

    // The default command encoding method
    'decode_commands' => \DeZio\Shell\Driver\Encoder\Base64Encoder::class
];
```

### Configuration Options

- **logging**: Enable/disable logging of SSH operations
- **trimOutput**: Whether to trim whitespace from command output
- **timeout**: Connection timeout in seconds
- **throw_error**: Whether to throw exceptions on command errors
- **default_shell**: The default shell connection implementation
- **decode_commands**: The encoder used for command encoding

## Events

The package fires the following events:

- **BeforeShellExecute**: Fired before a shell command is executed
- **AfterShellExecute**: Fired after a shell command is executed
- **ShellConnected**: Fired when a new SSH connection is established

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

Created by Dennis Ziolkowski.

## Hosting

In case you need viable VPS Hosting, you can check out Prepaid-Hoster at https://www.prepaid-hoster.de/vserver/root-server-mieten.html
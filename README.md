# Laravel Shell

The README file provides an overview of the Laravel Shell package, which simplifies executing SSH commands and managing remote servers directly from a Laravel application. 

## Description
Laravel Shell allows you to transform Laravel models into SSH-capable objects, enabling seamless interaction with remote servers.

It uses phpseclib/phpseclib under the hood.

## Installation

Install via Composer:

```bash
composer require dezio/laravel-shell
```

Publish and customize the configuration with:

```bash
php artisan vendor:publish --provider="DeZio\Shell\ShellServiceProvider"
```

## Usage
The package provides an API to:

- Establish SSH connections to remote servers.
- Give models an interface to make them _SSH_able.
- Execute commands and retrieve their output.
- Manage files on remote servers (e.g., create, write, delete).

Below is a sample usage script:

```php
<?php

use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

const TMP_HELLO_PATH = '/tmp/hello.txt';
// Password can be a string or a phpseclib PrivateKey object
$server = new DynamicServer('192.168.1.2', 22, 'root', 'password');
$shell = SSH::addConnection($server);
$hostname = $shell->exec(['hostname']);

echo $hostname->getOutput();

$file = "Hello World!";
$shell->io()->writeFile(TMP_HELLO_PATH, $file);
$shell->io()->deleteFile(TMP_HELLO_PATH);
```

The returned response object (of type ShellResponse) provides additional details:
- getOutput(): Returns the standard output from the command.
- getError(): Returns any error messages or standard error output.
- getExitCode(): Returns the exit code (0 usually indicates success).
- isSuccess(): Boolean flag that indicates whether the command executed successfully.

## Advanced Usage

### SSH Key Authentication

If you prefer key-based authentication, use your SSH private key as shown below:

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

Any Laravel model can be used for managing SSH credentials by implementing the HasServerCredentials interface:

```php
use Illuminate\Database\Eloquent\Model;
use DeZio\Shell\Contracts\HasServerCredentials;
use DeZio\Shell\Authentication\Login;
use DeZio\Shell\Authentication\ServerCredentials;

class Server extends Model implements HasServerCredentials
{
    // Assuming the model has host, port, username and password_or_key properties.
    public function getServerCredentials(): ServerCredentials
    {
        $login = new Login($this->username, $this->password_or_key);
        return new ServerCredentials($login, $this->host, $this->port);
    }
}
```

## System Information Examples

### Parsing Current Load Average Using /proc/loadavg

Retrieve the load average by reading /proc/loadavg.
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

### Getting Memory Usage Using /proc/meminfo

Retrieve memory info by reading /proc/meminfo.
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

Publish and customize the configuration with:

```bash
php artisan vendor:publish --provider="DeZio\Shell\ShellServiceProvider"
```

### Configuration Details

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

Customize these options as needed.

## Contributing

Contributions are welcome. Fork, create a feature branch, write tests, and open a pull request.

## License

This package is open source and available under the MIT License.

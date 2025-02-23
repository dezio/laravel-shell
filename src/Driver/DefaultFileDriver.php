<?php
/**
 * File: DefaultFileDriver.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Driver;

use DeZio\Shell\Contracts\ShellConnection;
use DeZio\Shell\Contracts\ShellFileSystem;

class DefaultFileDriver implements ShellFileSystem
{
    private ShellConnection $connection;

    public function __construct(ShellConnection $connection)
    {
        $this->connection = $connection;
    }

    public function readFile(string $path): string
    {
        return $this->connection->exec([
            'cat',
            $path
        ])->getOutput();
    }

    public function writeFile(string $path, string $content): void
    {
        $base64Content = base64_encode($content);
        $this->connection->exec([
            'echo',
            $base64Content,
            '|',
            'base64',
            '-d',
            '>',
            $path
        ]);
    }

    public function appendFile(string $path, string $content): void
    {
        $base64Content = base64_encode($content);
        if (!$this->exists($path)) {
            $this->touch($path);
        }

        $this->connection->exec([
            'echo',
            $base64Content,
            '|',
            'base64',
            '-d',
            '>>',
            $path
        ]);
    }

    public function deleteFile(string $path): void
    {
        $this->connection->exec([
            'rm',
            $path
        ]);
    }

    public function createDirectory(string $path, bool $recursive = true): void
    {
        $this->connection->exec([
            'mkdir',
            $recursive ? '-p' : '',
            $path
        ]);
    }

    public function touch(string $path): void
    {
        $this->connection->exec([
            'touch',
            $path
        ]);
    }

    public function exists(string $path): bool
    {
        return $this->connection->exec([
            'test',
            '-e',
            $path
        ])->isSuccess();
    }
}

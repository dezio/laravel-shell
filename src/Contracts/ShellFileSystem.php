<?php
/**
 * File: ShellFileSystem.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

interface ShellFileSystem
{
    /**
     * Reads the contents of a file from the given file path.
     *
     * @param string $path The path to the file to be read.
     * @return string The contents of the file.
     * @throws \Exception If the file cannot be read or does not exist.
     */
    public function readFile(string $path): string;

    /**
     * Writes the given content to a file at the specified path.
     *
     * @param string $path The path to the file where the content should be written.
     * @param string $content The content to be written to the file.
     * @return void
     */
    public function writeFile(string $path, string $content): void;

    /**
     * Appends the given content to an existing file at the specified path.
     *
     * @param string $path The path to the file where the content should be appended.
     * @param string $content The content to be appended to the file.
     * @return void
     */
    public function appendFile(string $path, string $content): void;

    /**
     * Deletes the file located at the specified path.
     *
     * @param string $path The path to the file that should be deleted.
     * @return void
     */
    public function deleteFile(string $path): void;

    /**
     * Creates a directory at the specified path.
     *
     * @param string $path The path where the directory should be created.
     * @return void
     */
    public function createDirectory(string $path): void;

    /**
     * Checks if the specified path exists.
     *
     * @param string $path The path to check for existence.
     * @return bool True if the path exists, false otherwise.
     */
    public function exists(string $path): bool;
}

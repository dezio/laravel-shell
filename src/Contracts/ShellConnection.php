<?php
/**
 * File: ShellConnection.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;


use DeZio\Shell\Authentication\ServerCredentials;

interface ShellConnection
{
    /**
     * Retrieves the server credentials.
     *
     * @return ServerCredentials
     */
    public function getCredentials(): ServerCredentials;

    /**
     * Execute a shell command with the given arguments as one command and return the shell response.
     *
     * @param array $args The arguments to pass to the shell command.
     * @return ShellResponse The response from the shell command execution.
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
     * Processes the provided arguments to generate a JSON response.
     *
     * @param array $args An array of parameters required for JSON processing.
     *
     * @return array The resulting data as an associative array.
     */
    public function json(array $args): array;

    /**
     *
     */
    public function io(): ShellFileSystem;

    public function setTimeout(int $timeout);
}

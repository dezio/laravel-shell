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

    /**
     *
     */
    public function io(): ShellFileSystem;
}

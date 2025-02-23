<?php
/**
 * File: ShellResponse.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

/**
 * Interface ShellResponse
 *
 * Provides methods to interact with the result of a shell command execution.
 */
interface ShellResponse
{
    /**
     * Gets the output of the command.
     */
    public function getOutput(): string;

    /**
     * Gets the error message of the command.
     */
    public function getError(): string;

    /**
     * Gets the exit code of the command.
     */
    public function getExitCode(): int;

    /**
     * Determines if the operation was successful.
     *
     * @return bool
     */
    public function isSuccess(): bool;
}

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
 *
 * The response encapsulates:
 *   - The command output (via getOutput)
 *   - Any error messages (via getError)
 *   - The exit code of the command (via getExitCode)
 *   - A success flag (via isSuccess)
 */
interface ShellResponse
{
    /**
     * Gets the output of the command.
     *
     * This includes the standard output text.
     *
     * @return string
     */
    public function getOutput(): string;

    /**
     * Gets the error message of the command.
     *
     * This includes any errors or standard error text.
     *
     * @return string
     */
    public function getError(): string;

    /**
     * Gets the exit code of the command.
     *
     * Typically, an exit code of 0 indicates success.
     *
     * @return int
     */
    public function getExitCode(): int;

    /**
     * Determines if the operation was successful.
     *
     * Considers the exit code and error output.
     *
     * @return bool
     */
    public function isSuccess(): bool;
}

<?php
/**
 * File: CommandEncoder.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Contracts;

interface CommandEncoder
{
    /**
     * Encodes the command to be executed on the remote server.
     */
    public function encode(string $command): string;
}

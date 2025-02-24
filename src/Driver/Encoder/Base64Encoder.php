<?php
/**
 * File: Base64Encoder.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Driver\Encoder;

use DeZio\Shell\Contracts\CommandEncoder;

class Base64Encoder implements CommandEncoder
{
    /**
     * Encodes a given command into a base64 string and prepares it for execution in a bash-compatible format.
     *
     * @param string $command The command to be encoded.
     * @return string The encoded command wrapped in a bash execution string.
     */
    public function encode(string $command): string
    {
        $encoded = base64_encode($command);
        return "echo '$encoded' | base64 -d | bash";
    }
}

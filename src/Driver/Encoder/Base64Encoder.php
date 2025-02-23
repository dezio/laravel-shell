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
    public function encode(string $command): string
    {
        $encoded = base64_encode($command);
        return "echo '$encoded' | base64 -d | bash";
    }
}

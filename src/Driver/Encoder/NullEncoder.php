<?php
/**
 * File: NullEncoder.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Driver\Encoder;

use DeZio\Shell\Contracts\CommandEncoder;

class NullEncoder implements CommandEncoder
{
    public function encode(string $command): string
    {
        return $command;
    }
}

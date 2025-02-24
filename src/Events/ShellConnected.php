<?php
/**
 * File: ShellConnected.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Events;

use DeZio\Shell\Contracts\HasServerCredentials;
use DeZio\Shell\Contracts\ShellConnection;

class ShellConnected
{
    public function __construct(
        public readonly HasServerCredentials $credentials,
        public readonly ShellConnection $connection
    )
    {
        //
    }
}

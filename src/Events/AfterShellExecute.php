<?php
/**
 * File: BeforeShellExecute.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Events;

use DeZio\Shell\Contracts\ShellResponse;

class AfterShellExecute
{
    public function __construct(
        public ShellResponse $response
    )
    {
        //
    }
}

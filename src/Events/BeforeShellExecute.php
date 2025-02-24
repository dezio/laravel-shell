<?php
/**
 * File: BeforeShellExecute.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Events;

class BeforeShellExecute
{
    public function __construct(
        public string &$command
    )
    {
        //
    }
}

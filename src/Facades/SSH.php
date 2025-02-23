<?php
/**
 * File: SSH.php
 * Created: Feb 2025
 * Project: PPH-Virt-Manager
 */

namespace DeZio\Shell\Facades;

use DeZio\Shell\ShellContainer;
use Illuminate\Support\Facades\Facade;

/**
 * Class SSH
 *
 * @mixin ShellContainer
 */
class SSH extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ShellContainer::class;
    }
}

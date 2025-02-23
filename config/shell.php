<?php

return [
    // Whether logging is enabled
    'logging'         => true,

    // Whether to trim the output
    'trimOutput'      => true,

    // The timeout for the shell connection
    'timeout'         => 10,

    // Determines whether errors should be thrown
    'throw_error'     => false,

    // The default shell connection class
    'default_shell'   => \DeZio\Shell\Driver\DefaultShellConnection::class,

    // The default command encoding method
    'decode_commands' => \DeZio\Shell\Driver\Encoder\Base64Encoder::class
];

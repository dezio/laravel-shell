<?php

use DeZio\Shell\Dynamic\DynamicServer;
use DeZio\Shell\Facades\SSH;

const TMP_HELLO_PATH = '/tmp/hello.txt';
$server = new DynamicServer('192.168.1.2', 22, 'root', 'password');
$shell = SSH::addConnection($server);
$hostname = $shell->exec(['hostname']);

echo $hostname->getOutput();

$file = "Hello World!";
$shell->io()->writeFile(TMP_HELLO_PATH, $file);
$shell->io()->deleteFile(TMP_HELLO_PATH);

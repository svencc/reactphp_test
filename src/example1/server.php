<?php

// pipe a connection into itself
require \Application\Helper\FileSystem::normalizePathSeparator(__DIR__.'/../../vendor/autoload.php');


/** @var $loop React\EventLoop\StreamSelectLoop */
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);

$socket->on('connection', function ($conn) {
    $conn->pipe($conn);
});

echo "Socket server listening on port 4000.\n";
echo "You can connect to it by running: telnet localhost 4000\n";

$socket->listen(4000);
$loop->run();

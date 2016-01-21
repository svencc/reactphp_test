<?php
namespace Application\Logik\Server\Streamer;

use Symfony\Component\Console\Output;
/**
 * Created by PhpStorm.
 * User: svencc
 * Date: 13.12.15
 * Time: 13:32
 */
class Implementation
{

    public function start(Output\OutputInterface $output) {

        /* @var $_loop  \React\EventLoop\LoopInterface*/
        $loop = \React\EventLoop\Factory::create();

        // create a new socket
        $socket = new \React\Socket\Server($loop);

        // pipe a connection into itself
        $socket->on('connection', function (\React\Socket\Connection $conn) use($output, $loop) {
            
        	$output->writeln( 'CONNECTION ESTABLISHED: '.$conn->getRemoteAddress() );
        	//$infiniteStreamHandle	= fopen('/tmp/random', 'r');
        	$infiniteStreamHandle	= fopen('/dev/urandom', 'r');
        	
        	$fileToStream = new \React\Stream\Stream($infiniteStreamHandle, $loop);
            $output->writeln( 'streaming ...' );
        	//$conn->pipe($infiniteStreamHandle);
        	$fileToStream->pipe($conn);
            

        });

        echo "Socket server listening on port 4000.\n";
        echo "You can connect to it by running: telnet localhost 4000\n";

        $socket->listen(4000);
        $loop->run();

    }
}



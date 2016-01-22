<?php
namespace Application\Commands\Collection;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CommandClientChattyStart extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('client:chatty:start')
            ->setDescription('Starts the "clients server')
            ->addArgument(
                'number',
                InputArgument::OPTIONAL,
                'The number of parallel clients which shall connect to the server',
                1
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $numberOfForks = $input->getArgument('number');

        $manager        = new \Spork\ProcessManager();
        $batch          = range(1,$numberOfForks);

        $callback   = function($batchItem, $batchIndex, array $batch, \Spork\SharedMemory $sharedMemory) use($output) {
            $pid    = getmypid();


            $count=0;
            $socket = fsockopen('localhost', 4000);
            stream_set_blocking($socket,0);
            while(true) {
            	$message	= "{$pid}:{$batchItem}: {$count}\n";
                fwrite($socket, $message);
                $output->writeln('CLIENT SEND: '. $message);
                
                // wait 0.5 seconds
                usleep(500000);
                
                $read   = fread($socket, 9999);
                if($read !== false ) {
                    $output->writeln("CLIENT RECEIVED: {$read}");
                }
                $count++;
            }

            return "[PID: {$pid}][BATCH: {$batchIndex}]";
        };
        
        $strategy       = new \Spork\Batch\Strategy\ChunkStrategy($numberOfForks, true);

        /** @var \Spork\Fork $result */
        $result = $manager->process($batch, $callback, $strategy);
        $manager->wait();

        $resultList = $result->getResult();

        $output->writeln('resultList: '.var_export($resultList, true) );
        $output->writeln('finished');
    }
}


<?php
namespace Application\Commands\Collection;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CommandClientStart extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('client:start')
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
        $batch          = range(0,$numberOfForks);

        $callback   = function($batchItem, $batchIndex, array $batch, \Spork\SharedMemory $sharedMemory) use($output) {
            $pid    = getmypid();


            $count=0;
            $socket = fsockopen('localhost', 4000);
            stream_set_blocking($socket,0);
            while(true) {
                fwrite($socket, "{$pid}:{$batchItem}: {$count}\n");
                $read   = fread($socket, 9999);
                if($read !== false ) {
                    $output->writeln('RECEIVED: '. $read);
                }
                $count++;
            }
            /*
            $telnetStatus   = null;;
            $process    = new Process('telnet localhost 4000');
            $process->run();

            $output->writeln( 'GET CMD1: '. var_export( $process->getOutput() , true) );
            //while($process->isRunning()) {
            $counter=0;
            while(true) {
                //$process->setInput("asd");
                sleep(1);
                $output->writeln( 'GET CMD2: '. var_export( $process->getOutput() , true) );
                $process->clearOutput();

                $process->setInput($pid.' '.$counter."\n");
$counter++;
            }

            $telnetStatus = $process->getExitCode();



*/
            /*
            try {
                $process->mustRun();

            } catch(ProcessFailedException $pfe) {
                $telnetStatus   = $pfe->getMessage();
            }
            */
            $forkMessage    = <<<MESSAGE
----------------------------------
BATCH-ITEM:    {$batchItem}
BATCH-INDEX:   {$batchIndex}
TELNET-STATUS: {$telnetStatus}
----------------------------------
MESSAGE;

            $output->writeln($forkMessage);


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


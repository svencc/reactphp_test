<?php
namespace Application\Commands\Collection;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandServerEchoStart extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('server:echo:start')
            ->setDescription('Starts the "echo" server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $echoServer = new \Application\Logik\Server\SendEcho\Implementation();
        $echoServer->start($output);
    }
}
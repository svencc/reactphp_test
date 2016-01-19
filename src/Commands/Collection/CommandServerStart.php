<?php
namespace Application\Commands\Collection;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandServerStart extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('server:start')
            ->setDescription('Starts the "echo" server')
            /*
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )*/
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $echoServer = new \Application\Logik\Server\SendEcho\Implementation();
        $echoServer->start($output);
        $output->writeln('asd');
    }
}
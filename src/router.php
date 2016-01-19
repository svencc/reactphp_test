<?php
namespace Application;

use Application\Commands\Collector;
use Symfony\Component\Console\Application;

require_once __DIR__.'/bootstrap.php';

$application         = new Application();
$commandCollector   = new Collector();
$commands           = $commandCollector->collectCommands();

foreach ($commands as $command) {
    $application->add( $command );
}

$application->run();

<?php
namespace Application;

use Application\Commands\Collector;
use Symfony\Component\Console\Application;


require_once __DIR__.'/bootstrap.php';

$application		= new Application();
$commandCollector	= new Collector();
$commands			= $commandCollector->collectCommands();

foreach ($commands as $command) {
    $application->add( $command );
}

//generate_file('/tmp/random', 9999999);
$application->run();


function generate_file($file_name, $size_in_bytes)
{
	$data = str_repeat(rand(0,9), $size_in_bytes);
	file_put_contents($file_name, $data); //writes $data in a file
}
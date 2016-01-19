<?php
namespace Application\Commands;

class Collector
{
    public function collectCommands() {

        $commandDirectoryIterator   = new \RecursiveDirectoryIterator(__DIR__);
        $iterator                   = new \RecursiveIteratorIterator($commandDirectoryIterator)   ;

        /** @var    $foundClasses \Symfony\Component\Console\Command\Command */
        $foundClasses = array();


        /** @var    \DirectoryIterator   $fileInfo   */
        foreach($iterator as $name => $fileInfo ) {
            if( $fileInfo->isDir() ) {
                continue;
            }

            $namespace  = __NAMESPACE__;
            $className  =  $fileInfo->getFilename();
            $className  = substr($className, 0, strpos($className, '.'));

            $classSymbol    = "{$namespace}\\Collection\\{$className}";

            if ( class_exists($classSymbol, true) ) {
                $instance = new $classSymbol();

                if( is_subclass_of($instance, 'Symfony\Component\Console\Command\Command') ) {
                    $foundClasses[]   = $instance;
                }
            }

        }

        return $foundClasses;
    }
}
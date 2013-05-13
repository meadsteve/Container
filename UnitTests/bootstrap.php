<?php
include_once "vendor/autoload.php";

/**
 * Creates an psr-0 style autoloader from the baseDir root.
 * @param $baseDir
 * @return callable
 */
function buildAutoload($baseDir)
{
    return function($className) use ($baseDir) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		$Path = $baseDir . DIRECTORY_SEPARATOR . $fileName;
		if (file_exists($Path))
        	require $Path;
    };
}

spl_autoload_register(buildAutoload(__DIR__ . "/../src/"));

<?php

spl_autoload_register(function ($class): void {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Impossible de charger la classe : $class");
    }
});
?>
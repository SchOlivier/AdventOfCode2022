<?php
spl_autoload_register(function ($class_name) {

    $baseDir = str_replace(DIRECTORY_SEPARATOR, '\\', __DIR__);
    $baseNameSpace = 'App';

    if (strpos($class_name, $baseNameSpace) === 0) {
        $file = substr_replace($class_name, $baseDir, 0, strlen($baseNameSpace));

        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';
        require $file;
    }
});

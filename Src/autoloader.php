<?php

spl_autoload_register(function ($class) {
    $classAsPath = str_replace('\\', '/', $class);
    $paths = [
        __DIR__ . '/',
        __DIR__ . '/elements/',
        __DIR__ . '/component/',
    ];

    foreach ($paths as $path) {
        if (file_exists($path . $classAsPath . '.php')) {
            require $path . $classAsPath . '.php';
            return;
        }
    }

    throw new Exception("Could not find class $class in Scratchy repository.");
});
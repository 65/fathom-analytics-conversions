<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5381b5c771a1df0097baf997f5d1565c
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Appsero\\' => 
        array (
            0 => __DIR__ . '/..' . '/appsero/client/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5381b5c771a1df0097baf997f5d1565c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5381b5c771a1df0097baf997f5d1565c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5381b5c771a1df0097baf997f5d1565c::$classMap;

        }, null, ClassLoader::class);
    }
}

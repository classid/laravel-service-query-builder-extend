<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit73e36ceeb6bee3105bba89c965ad2830
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Classid\\LaravelQueryBuilderExtend\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Classid\\LaravelQueryBuilderExtend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit73e36ceeb6bee3105bba89c965ad2830::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit73e36ceeb6bee3105bba89c965ad2830::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit73e36ceeb6bee3105bba89c965ad2830::$classMap;

        }, null, ClassLoader::class);
    }
}

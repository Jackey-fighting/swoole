<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9db0c5037e676af9680da00f46257515
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pheanstalk\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pheanstalk\\' => 
        array (
            0 => __DIR__ . '/..' . '/pda/pheanstalk/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9db0c5037e676af9680da00f46257515::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9db0c5037e676af9680da00f46257515::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

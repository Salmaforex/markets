<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbbb1f52f4aa45b1f82ad4b851e9dce2f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbbb1f52f4aa45b1f82ad4b851e9dce2f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbbb1f52f4aa45b1f82ad4b851e9dce2f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

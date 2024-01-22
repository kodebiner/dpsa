<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 *       the values in this file will overwrite the framework's values.
 *
 * NOTE: This class is required prior to Autoloader instantiation,
 *       and does not extend BaseConfig.
 *
 * @immutable
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'         => APPPATH
     *   ];
     *
     * @var array<string, array<int, string>|string>
     * @phpstan-var array<string, string|list<string>>
     */
    public $psr4 = [
        APP_NAMESPACE                   => APPPATH,
        'Config'                        => APPPATH . 'Config',
        'Myth\Auth'                     => APPPATH . 'ThirdParty/myth-auth/src',
        'mpdf/psr-http-message-shim'    => APPPATH . 'ThirdParty/psr-http-message-shim/src',
        'mpdf/psr-log-aware-trait'      => APPPATH . 'ThirdParty/psr-log-aware-trait-3.x/src',
        'myclabs/deep-copy'             => APPPATH . 'ThirdParty/DeepCopy-1.11.1/src',
        'paragonie/random_compat'       => APPPATH . 'ThirdParty/random_compat-2.0.21/lib',
        'psr/http-message'              => APPPATH . 'ThirdParty/http-message-2.0/src',
        'psr/log'                       => APPPATH . 'ThirdParty/log-3.0.0/src',
        'setasign\Fpdi'                 => APPPATH . 'ThirdParty/FPDI-2.6.0/src',
        'Mpdf'                          => APPPATH . 'ThirdParty/mpdf-8.1.0/src',
        'PhpOffice\PhpSpreadsheet'      => APPPATH . 'ThirdParty/phpoffice',
        'ZipStream'                     => APPPATH . 'ThirdParty/ZipStream-PHP-3.1.0/src',
        'Complex'                       => APPPATH . 'ThirdParty/PHPComplex-3.0.2/classes/src',
        'Matrix'                        => APPPATH . 'ThirdParty/PHPMatrix-3.0.1/classes/src',
        'Psr\Http\Client'               => APPPATH . 'ThirdParty/http-client-master/src',
        'Psr\Http\Message'              => APPPATH . 'ThirdParty/http-factory-master/src',
        'Psr\SimpleCache'               => APPPATH . 'ThirdParty/simple-cache-master/src',
        'voku\helper'                   => APPPATH . 'ThirdParty/anti-xss-master/src/voku/helper',
        // 'mockery/mockery'               => APPPATH . 'ThirdParty/mockery-1.6.7/library',
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $helpers = [];
}

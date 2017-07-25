<?php

use \Nettools\JsDocPhp\Controller;
use \Nettools\JsDocPhp\Filesystem\Files;



// TODO remove
include '../../../../libc-test/vendor/autoload.php';


// ##### SET THIS WITH A ROOT FOLDER FOR DOC OUTPUT #####
const OUTPUT_FOLDER = __DIR__ . '/output/';

// ##### SET THIS WITH A CACHE FOLDER FOR TWIG TEMPLATES #####
const CACHE_FOLDER = __DIR__ . '/cache/';



// composer autoload
if ( !class_exists('\Nettools\JsDocPhp\Controller') )
    if ( file_exists(__DIR__ . '/../../../autoload.php') )
        include_once __DIR__ . '/../../../autoload.php';
    else
        die('Composer autoload is not found in ' . realpath(__DIR__ . '/../../../'));




try 
{
    $package = Controller::process(
            new Files(rtrim(__DIR__,'/') . '/res/', ['source.js', 'subfolder/simple.js']), 
            OUTPUT_FOLDER, 
            CACHE_FOLDER, 
            new \Psr\Log\NullLogger(), 
            ['strict'=>true]
        );
    echo "<a target=\"_blank\" href=\"output/index.html\">View generated doc</a>";
}
catch (\Nettools\JsDocPhp\Exceptions\ParserException $e)
{
    echo "<pre>";
    echo $e->getMessage();
    echo "\n";
    echo $e->getSourcecode();
    echo "\n";
    echo "</pre>";
}
catch (Throwable $e)
{
    echo $e->getMessage();
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}
?>
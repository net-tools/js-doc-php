<?php

use \Nettools\JsDocPhp\Controller;
use \Nettools\JsDocPhp\Filesystem\Files;



// including autoload
if ( file_exists(__DIR__ . '/../../../autoload.php') )
    include_once __DIR__ . '/../../../autoload.php';
else
    die('Composer autoload is not found in ' . realpath(__DIR__ . '/../../../'));



// ##### BEFORE RUNNING THIS SAMPLE, OPEN THE consts.php FILE AND UPDATE THE FOLDER PATHS #####
include "consts.php";




if ( $_REQUEST['cmd'] )
    try 
    {
        switch ( $_REQUEST['cmd'] )
        {
            // generate ok with strict mode
            case 'gen_ok': 
                $package = Controller::process(
                        new Files(rtrim(__DIR__,'/') . '/res/', ['source.js', 'subfolder/simple.js']), 
                        K_OUTPUT_FOLDER, 
                        K_CACHE_FOLDER, 
                        new \Psr\Log\NullLogger(), 
                        ['strict'=>true]
                    );

                $output = "<a target=\"_blank\" href=\"output/index.html\">View generated doc</a>";
                break;
                
                
                
            // samples for errors
            case 'gen_ko':
                $package = Controller::process(
                        new Files(rtrim(__DIR__,'/') . '/res/', ['source-ko-' . $_REQUEST['file'] . '.js']), 
                        K_OUTPUT_FOLDER, 
                        K_CACHE_FOLDER, 
                        new \Psr\Log\NullLogger(), 
                        ['strict'=>true]
                    );

                $output = "<a target=\"_blank\" href=\"output/index.html\">View generated doc</a>";
                break;
                
                
            // unknown command
            default:
                $output = "Unknown command";
                break;
        }

    }
    catch (\Nettools\JsDocPhp\Exceptions\ParserException $e)
    {
        $output = $e->getMessage() . "\n" .  $e->getSourcecode() . "\n";
    }
    catch (Throwable $e)
    {
        $output = $e->getMessage();
    }    


?>
<html>
    <head>
        <style>
            #output {
                background-color: whitesmoke;
                border:1px solid lightgray;
                padding:1em;
                white-space: pre-wrap;
            }
            
            body {
                font-size: 16px;
                font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
            }
            
            a {
                color:mediumblue;
            }
        </style>
    </head>
    <body>
        <div id="output"><?php echo $output; ?></div>
        
        <hr>
        
        <ul>
            <li><a href="?cmd=gen_ok">Generate JS doc with success</a></li>
            <li><a href="?cmd=gen_ko&file=notopleveldocblock">Generate JS doc with error : no top-level docblock</a></li>
            <li><a href="?cmd=gen_ko&file=noname">Generate JS doc with error : no name for docblock</a></li>
            <li><a href="?cmd=gen_ko&file=classcontent">Generate JS doc with error : class content can't be identified</a></li>
            <li><a href="?cmd=gen_ko&file=staticparent">Generate JS doc with error : in a static declaration, parent not found</a></li>
            <li><a href="?cmd=gen_ko&file=grammar">Generate JS doc with error : keyword not allowed in this context</a></li>
        </ul> 
    </body>
</html>






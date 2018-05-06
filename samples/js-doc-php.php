<?php

use \Nettools\JsDocPhp\Controller;
use \Nettools\JsDocPhp\Filesystem\Files;



// including autoload
if ( file_exists(__DIR__ . '/../../../autoload.php') )
    include_once __DIR__ . '/../../../autoload.php';
else
    die('Composer autoload is not found in ' . realpath(__DIR__ . '/../../../'));



// ##### BEFORE RUNNING THIS SAMPLE, OPEN THE consts.php FILE AND UPDATE THE FOLDER PATHS #####
// THEN UNCOMMENT THE FOLLOWING LINE SO THAT THE const.php COULB BE INCLUDED
include "consts.php";


if ( !defined('K_OUTPUT_FOLDER') )
    $output = "<span style=\"font-weight:bold; color:firebrick;\">Please update samples/consts.php file and uncomment the 'include \"consts.php\"' line.</span>";
else
    if ( $_REQUEST['cmd'] )
        try 
        {
			class JsDocLogger extends \Psr\Log\AbstractLogger
			{
				public $logdata = [];


				public function log($level, $message, array $context = array())
				{
					$docblock = $context['docblock'];
					unset($context['docblock']);
					$this->logdata[] = ['level'=>$level, 'message'=>$message, 'docblock'=>$docblock, 'context'=>$context];
				}
			}



			// create ram logger
			$logger = new JsDocLogger(); 
			
			
			
            switch ( $_REQUEST['cmd'] )
            {
                // generate ok with strict mode
                case 'gen_ok': 
                    $package = Controller::process(
                            new Files(rtrim(__DIR__,'/') . '/res/', ['source.js', 'subfolder/simple.js']), 
                            K_OUTPUT_FOLDER, 
                            K_CACHE_FOLDER, 
                            $logger, 
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
                            $logger, 
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
		finally
		{
			$i = 1;
			$log = array();
			foreach ( $logger->logdata as $event )
			{
				$line = "[$i] " . strtoupper($event['level']) . ' ' . $event['message'];
				
				if ( $event['docblock'] )
					$line .= "\n" . $event['docblock'] . "\n\n";
				
				if ( count($event['context']) )
					$line .= print_r($event['context'], true) . "\n\n";
				
				$log[] = $line;
				$i++;
			}
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
			
			#log {
				margin-top:1em;
                background-color: black;
                border:1px solid lightgray;
                padding:1em;
				color:white;
				font-style: italic;
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
		<div id="log">Log output :<br>====<br><br><?php if ( $log ) echo implode('<br>-<br>',$log); ?></div>
        
        <hr>
        
        <ul>
            <li><a href="?cmd=gen_ok">Generate JS doc with success</a></li>
            <li><a href="?cmd=gen_ko&file=notopleveldocblock">Generate JS doc with error : no top-level docblock</a></li>
            <li><a href="?cmd=gen_ko&file=notopleveldocblockkind">Generate JS doc with error : no top-level docblock kind</a></li>
            <li><a href="?cmd=gen_ko&file=noname">Generate JS doc with error : no name for docblock</a></li>
            <li><a href="?cmd=gen_ko&file=classcontent">Generate JS doc with error : class content can't be identified</a></li>
            <li><a href="?cmd=gen_ko&file=staticparent">Generate JS doc with error : in a static declaration, parent not found</a></li>
            <li><a href="?cmd=gen_ko&file=grammar">Generate JS doc with error : keyword not allowed in this context</a></li>
        </ul> 
    </body>
</html>






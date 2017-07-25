<?php

use \Nettools\JsDocPhp\Controller;


// TODO autoload
//$autoload = rtrim(realpath(__DIR__ . "/../../../"),'/') . '/vendor/autoload.php';
$autoload = rtrim(realpath(__DIR__ . "/../../../"),'/') . '/../libc-jsdoc/vendor/autoload.php';
if ( !file_exists($autoload) )
    die("Composer autoload not found in '$autoload'");

include $autoload;




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


try
{
    
    
    // test paramaters
    if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?')))
    {
        ?>
        The config file parameter is mandatory.

        Correct usage is :
            <?php echo $argv[0]; ?> <path_to_config_file>

            <path_to_config_file> : Full path to config file to read

            With options --help, -help, -h,
            and -?, this help screen is displayed

        <?php
        die();
    } 
    
    
    // test file
    if ( !file_exists($argv[1]) )
        die('Config file does not exists : ' . $argv[1]);
    

    Controller::processFromConfig(file_get_contents($argv[1]), $logger);

}
catch(\Nettools\JsDocPhp\Exceptions\ParserException $e)
{
    echo "=== EXCEPTION === " . $e->getMessage();
    echo "\n";
    echo "\n";
    echo str_pad('', 80, '-');
    echo "\n";
    echo $e->getSourcecode();
    echo "\n";
    echo str_pad('', 80, '-');
    echo "\n";
}
catch(\Throwable $e)
{
    echo "=== EXCEPTION === " . $e->getMessage();
}
finally
{
    $i = 1;
    foreach ( $logger->logdata as $event )
    {
        echo "[$i] " . strtoupper($event['level']) . ' ' . $event['message'] . "\n" . $event['docblock'] . "\n\n" . (count($event['context']) ? print_r($event['context'], true) . "\n" : '') . "\n\n";
        $i++;
    }
}

?>
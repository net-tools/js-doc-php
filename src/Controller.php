<?php

namespace Nettools\JsDocPhp;



/**
 * Controller class
 */
class Controller {
    
    /**
     * Parse files and output doc
     *
     * Available options are :
     *   - strict (bool) : if set to false, if an error occurs, and the parsing can go on, we don't stop the process
     *
     * @param Filesystem\Files $files List of files as a Filesystem\Files object
     * @param string $output Root directory for output folder
     * @param string $cache Cache folder
     * @param \Psr\Log\LoggerInterface $log Log interface to log warning in non-strict mode
     * @param string[] $options Options for parser, as an associative array
     * @throws Exceptions\Exception Exception thrown if an error occured
     */
    static public function process(Filesystem\Files $files, $output, $cache, \Psr\Log\LoggerInterface $log, $options = array())
    {
        $parser = new Parser($options);
        
        // parse all files
        $ret = [];
        foreach ( $files->files as $f )
            $ret[] = $parser->parse($files->root, $f, $log);
        
        
        TemplateGenerator::output($ret, $parser->projectClassesNamespaces, rtrim($output, '/') . '/', $cache);
    }
    
    
    
    /**
     * Parse files and output doc by reading config data from a json string
     *
     * The json string must declare properties output (string), files (object with files and root properties) and cache (string).
     *
     * @param string $json
     * @param \Psr\Log\LoggerInterface $log Log interface to log warning in non-strict mode
     * @throws Exceptions\Exception Exception thrown if an error occured
     */
    static public function processFromConfig($json, \Psr\Log\LoggerInterface $log)
    {
        $config = json_decode($json);
        if ( $config == null )
            throw new Exceptions\Exception('Can\'t read config from Json file');

        return self::process(Filesystem\Files::fromJson($config->files), $config->output, $config->cache, $log, (array)$config->options);
    }
    
}



?>
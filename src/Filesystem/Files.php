<?php

namespace Nettools\JsDocPhp\Filesystem;



/**
 * Files list class
 */
class Files {
    
    
    /**
     * List of files, with paths relative to $root
     *
     * @var string[]
     */
    public $files = [];
    
    
    
    /**
     * Path to root folder for all files
     *
     * @var string
     */
    public $root = null;
    
    
    
    /**
     * Constructor
     * 
     * @param string $root
     * @param string[] $files
     */
    public function __construct($root, array $files)
    {
        $this->root = rtrim($root,'/') . '/';
        $this->files = array_map(function($f){return ltrim($f, '/');}, $files);
    }
    
    
    
    /**
     * Save object to JSON
     * 
     * @return string File list as Json
     */
    public function toJson()
    {
        return json_encode($this);
    }
    
    
    
    /** 
     * Restore object from Json
     *
     * @param \Stdclass $o Litteral object representing the Files object as json
     * @return Files
     */
    public static function fromJson(\Stdclass $o)
    {
        return new Files($o->root, $o->files);
    }
    
}



?>
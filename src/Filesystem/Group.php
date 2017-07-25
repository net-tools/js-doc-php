<?php

namespace Nettools\JsDocPhp\Filesystem;



/**
 * Group of files class
 */
class Group extends Files {
    
    /**
     * Constructor
     * 
     * @param string $root Root path for all files ; must be coherent with the root property of Files objects in $files array
     * @param Files[] $files
     */
    public function __construct($root, array $files)
    {
        $thefiles = [];
        $root = rtrim($root, '/').'/';
        
        foreach ( $files as $filesobj )
        {
            if ( !$filesobj instanceof Files )
                throw new \Nettools\JsDocPhp\Exceptions\FilesystemException('A group item is not a Filesystem\\Files object');
            
            if ( $files->root != $root )
                throw new \Nettools\JsDocPhp\Exceptions\FilesystemException('Root folder of a Filesystem\\Files object is not the same as the root folder of group object');
            
            $thefiles = array_merge($thefiles, $filesobj->files);
        }
            
        
        parent::__construct(root, $thefiles);
    }
    
    
}



?>
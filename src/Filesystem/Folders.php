<?php

namespace Nettools\JsDocPhp\Filesystem;



/**
 * Folders list class
 */
class Folders extends Files {
        
    /**
     * List files in a folder
     * 
     * @param string $root Root folder for all folders
     * @param string $folder
     * @return string[] Returns an array of file paths
     */
    public static function listFolderFiles($root, $folder)
    {
        $root = rtrim($root, '/') . '/';
        $files = glob($root . rtrim($folder,'/') . '/*.js');
        
        if ( !is_array($files) )
            throw new \Nettools\JsDocPhp\Exceptions\FilesystemException("Can't list files in '$folder'.");

        
        // remove full path, and keep only relative path to $root
        return array_map(function($f) use ($root) {return str_replace($root, '', $f);}, $files);
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string $root Root folder for all folders
     * @param string[] $folders
     */
    public function __construct($root, array $folders)
    {
        $files = [];
        foreach ( $folders as $folder )
            $files = array_merge($files, self::listFolderFiles($root, $folder));

        parent::__construct($root, $files);
    }
    
    
}



?>
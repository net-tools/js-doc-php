<?php

namespace Nettools\JsDocPhp\Filesystem;



/**
 * Recursive folders list class
 */
class RecursiveFolders extends Folders {
        
    /**
     * List subfolders recursively
     * 
     * @param string $root Root folder for all folders
     * @param string $folder
     * @return string[] Returns an array of subfolders
     */
    public static function listSubfolders($root, $folder)
    {
        $folders = glob(rtrim($root, '/') . '/' . rtrim($folder,'/') . '/*', GLOB_ONLYDIR);
        if ( !is_array($folders) )
            throw new \Nettools\JsDocPhp\Exceptions\FilesystemException("Can't list subfolders of '$folder'.");
        
        $subfolders = [];

        
        // remove fullpath and keep only paths relative to $root in array
        $folders = array_map(function($f) use ($root) {return str_replace($root, '', $f);}, $folders);
        
        foreach ( $folders as $subf )
            $subfolders = array_merge($subfolders, self::listSubfolders($root, $subf));

        return $subfolders;
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string $root Root folder for all folders
     * @param string[] $folders
     */
    public function __construct($root, array $folders)
    {
        $allfolders = $folders;
        
        // get a list of folders (recursively)
        foreach ( $folders as $folder )
            $allfolders = array_merge($allfolders, self::listSubfolders($root, $folder));

        parent::__construct($root, $allfolders);
    }
    
    
}



?>
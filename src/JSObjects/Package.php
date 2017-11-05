<?php

namespace Nettools\JsDocPhp\JSObjects;



use \Nettools\JsDocPhp\Exceptions\Exception;





/**
 * Package object (a Javascript file of classes)
 */
class Package extends JSObject {
    
    /** 
     * Filename
     * 
     * @var string
     */
    public $file = null;
    
    
    
    /**
     * Array of classes belonging to the package
     *
     * @var ClassObject[] 
     */
    public $classes = [];
    
    
    
    /**
     * Array of namespaces belonging to the package
     *
     * @var NamespaceObject[] 
     */
    public $namespaces = [];
    
    
    
    /** Array of functions belonging to the package
     * 
     * @var Func[]
     */
    public $functions = [];
    
    
    
    /**
     * Is the package empty ?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !(count($this->classes) || count($this->functions) || count($this->namespaces));
    }
    
    
    
    /**
     * Associate an object with the class
     *
     * @param JSObject $obj
     */
    public function takeOwnership(JSObject $obj)
    {
        parent::takeOwnership($obj);
        
        
        if ( $obj instanceof ClassObject )
            $this->classes[] = $obj;
        else if ( $obj instanceof NamespaceObject )
            $this->namespaces[] = $obj;
        else if ( $obj instanceof Func )
            $this->functions[] = $obj;
        else
            throw new Exception("Can't take ownership of '$obj->name'.");
    }
}



?>
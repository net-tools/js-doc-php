<?php

namespace Nettools\JsDocPhp\JSObjects;



/**
 * Top-level object for javascript parser
 */
class JSObject {
    
    /**
     * Name of object (method, property, class)
     *
     * @var string
     */
    public $name = null;
    
    
    
    /** 
     * Reference to parent object
     * 
     * @var JSObject
     */
    public $parent = null;
    
    
    
    /** 
     * Short description
     *
     * @var string
     */
    public $shortDescription = null;
    
    
    
    /** 
     * Long description
     *
     * @var string
     */
    public $description = null;
    
    
    
    /** 
     * Constructor
     * 
     * @param JSObject $parent
     * @param string $name
     * @param string $description
     */
    public function __construct($parent, $name = null, $shortDescription = null)
    {
        $this->name = $name;
        $this->parent = $parent;
        $this->shortDescription = $shortDescription;
    }
    
    
    
    /**
     * Associate an object with another one
     *
     * @param JSObject $obj
     */
    public function takeOwnership(JSObject $obj)
    {
        $obj->parent = $this;
    }
    
}



?>
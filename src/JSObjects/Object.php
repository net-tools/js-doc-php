<?php

namespace Nettools\JsDocPhp\JSObjects;



/**
 * Top-level object for javascript parser
 */
class Object {
    
    /**
     * Name of object (method, property, class)
     *
     * @var string
     */
    public $name = null;
    
    
    
    /** 
     * Reference to parent object
     * 
     * @var Object
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
     * @param Object $parent
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
     * @param Object $obj
     */
    public function takeOwnership(Object $obj)
    {
        $obj->parent = $this;
    }
    
}



?>
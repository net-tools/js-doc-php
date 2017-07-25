<?php

namespace Nettools\JsDocPhp\JSObjects;


use \Nettools\JsDocPhp\Exceptions\Exception;




/**
 * Class object
 */
class ClassObject extends Object {

    /**
     * Methods
     *
     * @var Method[]
     */
    public $methods = [];
    
    
    
    /**
     * Properties
     *
     * @var Property[]
     */
    public $properties = [];
    
    
    
    /** 
     * Constructor parameters
     *
     * @var Parameter[]
     */
    public $constructorParameters = [];
    
    
    
    /**
     * Extends class
     *
     * @var ExtendsClass
     */
    public $extendsClass = null;
    
    
    
    /**
     * Associate an object with the class
     *
     * @param Object $obj
     */
    public function takeOwnership(Object $obj)
    {
        parent::takeOwnership($obj);
        
        if ( $obj instanceof Parameter )
            $this->constructorParameters[] = $obj;
        else if ( $obj instanceof Method )
            $this->methods[] = $obj;
        else if ( $obj instanceof Property )
            $this->properties[] = $obj;
        else if ( $obj instanceof ExtendsClass )
            $this->extendsClass = $obj;
        else
            throw new Exception("Can't take ownership of '$obj->name'.");
    }
}



?>
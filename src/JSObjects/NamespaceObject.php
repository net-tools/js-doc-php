<?php

namespace Nettools\JsDocPhp\JSObjects;


use \Nettools\JsDocPhp\Exceptions\Exception;




/**
 * Namespace object
 */
class NamespaceObject extends Object {

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
     * Associate an object with the namespace
     *
     * @param Object $obj
     */
    public function takeOwnership(Object $obj)
    {
        parent::takeOwnership($obj);
        
        if ( $obj instanceof Method )
            $this->methods[] = $obj;
        else if ( $obj instanceof Property )
            $this->properties[] = $obj;
        else
            throw new Exception("Can't take ownership of '$obj->name'.");
    }
}



?>
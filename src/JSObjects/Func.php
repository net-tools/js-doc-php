<?php

namespace Nettools\JsDocPhp\JSObjects;



/**
 * Function object
 */
class Func extends Object {

    /**
     * Function parameters
     *
     * @var Parameter[]
     */
    public $parameters = [];
    
    
    /**
     * Return value
     *
     * @var ReturnValue
     */
    public $returnValue = null;    
    
    
    /** 
     * Throw value
     *
     * @var ThrowValue
     */
    public $throwValue = null;
    
    
    
    /**
     * Associate an object with the function
     *
     * @param Object $obj
     */
    public function takeOwnership(Object $obj)
    {
        parent::takeOwnership($obj);
        
        
        if ( $obj instanceof ReturnValue )
            $this->returnValue = $obj;
        else if ( $obj instanceof ThrowValue )
            $this->throwValue = $obj;
        else if ( $obj instanceof Parameter )
            $this->parameters[] = $obj;
        else
            throw new \Nettools\JsDocPhp\Exceptions\Exception("Can't take ownership of '$obj->name'.");
    }
}



?>
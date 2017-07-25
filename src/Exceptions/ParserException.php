<?php

namespace Nettools\JsDocPhp\Exceptions;




/**
 * Parser exception
 */
class ParserException extends Exception
{
    /**
     * Faulty source code 
     *
     * @var string
     */
    protected $_sourcecode = null;
    
    
    
    /**
     * Constructor
     * 
     * @param string $message
     * @param string $sourcecode
     */
    public function __construct($message, $sourcecode)
    {
        parent::__construct($message);
        $this->_sourcecode = $sourcecode;
    }
    
    
    
    /** 
     * Accessor for sourcecode property
     *
     * @return string
     */
    public function getSourcecode()
    {
        return $this->_sourcecode;
    }
}


?>
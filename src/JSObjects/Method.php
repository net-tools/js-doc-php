<?php

namespace Nettools\JsDocPhp\JSObjects;



/**
 * Class method object
 */
class Method extends Func {

    
    /**
     * Is method static ?
     *
     * @var bool
     */
    public $staticMethod = false;
    
    
    /**
     * Is this a prototype object method ?
     *
     * @var bool
     */
    public $prototypeMethod = false;
    
}



?>
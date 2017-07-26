<?php

namespace Nettools\JsDocPhp\Grammar;



/**
 * Grammar object
 */
class Grammar {

    /**
     * Grammar as an array of Item objects
     * 
     * @var Item[]
     */
    protected $_grammar = [];
    
    
    
    /**
     * Convert a \Nettools\JsDocPhp\JSObjects\Object to a grammar item
     * 
     * @param \Nettools\JsDocPhp\JSObjects\Object $jso
     * @return Item|null
     */
    public function getItem(\Nettools\JsDocPhp\JSObjects\Object $jso)
    {
        $itemname = strtolower(substr(strrchr(get_class($jso),'\\'),1));
        return $this->_grammar[$itemname];
    }
    
    
    
    /**
     * Check a docblock item against the grammar and its parent docblock
     *
     * @param \Nettools\JsDocPhp\JSObjects\Object $child
     * @param \Nettools\JsDocPhp\JSObjects\Object $docblock
     * @return bool Returns true if the checking is good ; if not, an exception is thrown
     * @throws \Nettools\JsDocPhp\Exceptions\GrammarException
     */     
    public function check(\Nettools\JsDocPhp\JSObjects\Object $child, \Nettools\JsDocPhp\JSObjects\Object $docblock)
    {
        // check if child found in grammar
        if ( is_null($c_item = $this->getItem($child)) )
            throw new \Nettools\JsDocPhp\Exceptions\GrammarException("Docblock item '" . substr(strrchr(get_class($child),'\\'),1) . "' is unknown.");

        // check if docblock found in grammar
        if ( is_null($d_item = $this->getItem($docblock)) )
            throw new \Nettools\JsDocPhp\Exceptions\GrammarException("Docblock item '" . substr(strrchr(get_class($docblock),'\\'),1) . "' is unknown.");
            
        // check if this child is allowed in docblock
        if ( !$d_item->isChildAllowed($c_item) )
            throw new \Nettools\JsDocPhp\Exceptions\GrammarException("Docblock item '" . substr(strrchr(get_class($child),'\\'),1) . "' can't be a child of docblock '" . substr(strrchr(get_class($docblock),'\\'),1) . "'.");
        
        return true;
    }
    
    
    
    /**
     * Build the grammar 
     *
     * @return Item[] Returns an array of grammar items, indexed by item name
     */     
    static function build()
    {
        // create all required items
        $items = [];
        $items[] = $i_classobject = new Item('classobject', false, 1);
        $items[] = $i_package = new Item('package', false, 1);
        $items[] = $i_extendsclass = new Item('extendsclass', false, 1);
        $items[] = $i_func = new Item('func', false, 1);
        $items[] = $i_method = new Item('method', false, 1);
        $items[] = $i_namespaceobject = new Item('namespaceobject', false, 1);
        $items[] = $i_parameter = new Item('parameter', false, 1/*'n'*/);
        $items[] = $i_property = new Item('property', false, 1);
        $items[] = $i_returnvalue = new Item('returnvalue', false, 1);
        $items[] = $i_throwvalue = new Item('throwvalue', false, 1);
        
        
        // link items
        $i_package->addChildItems([$i_classobject, $i_func, $i_namespaceobject]);
        $i_classobject->addChildItems([$i_extendsclass, $i_method, $i_property, $i_parameter]);
        $i_func->addChildItems([$i_parameter, $i_returnvalue, $i_throwvalue]);
        $i_method->addChildItems([$i_parameter, $i_returnvalue, $i_throwvalue]);
        $i_namespaceobject->addChildItems([$i_method, $i_property]);
        
        $ret = [];
        foreach ( $items as $i )
            $ret[$i->name] = $i;
        
        $g = new Grammar();
        $g->_grammar = $ret;
        
        return $g;
    }
}

?>
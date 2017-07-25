<?php

namespace Nettools\JsDocPhp\Grammar;



/**
 * Grammar item
 */
class Item {
    
    /**
     * Item name
     *
     * @var string
     */
    public $name = null;
    
    
    /**
     * Is item mandatory ?
     * 
     * @var bool
     */
    public $mandatory = null;
    
    
    /** 
     * Cardinality of item (a numeric >= 1 or the string 'n') in its parent context (= the docblock where it is mentionned)
     *
     * @var int|string
     */     
    public $cardinality = null;
    
    
    /** 
     * Allowed childs of this item in its parent context (= the docblock where it is mentionned)
     *
     * @var Item[]
     */
    public $childs = [];
    
    

    /**
     * Create the item
     *
     * @param string $name
     * @param bool $mandatory Is this item mandatory in its parent context (= the docblock where it is mentionned) ?
     * @param string $cardinality Cardinality of item in its parent (= the docblock where it is mentionned) ; must be set with either a numeric (>= 1) or 'n'
     */
    public function __construct($name, $mandatory, $cardinality)
    {
        $this->name = $name;
        $this->mandatory = $mandatory;
        $this->cardinality = $cardinality;
    }
    
    
    
    /**
     * Test if a child item is an allowed child of this item
     * 
     * @param Item $child
     * @return bool
     */
    public function isChildAllowed(Item $child)
    {
        foreach ( $this->childs as $c )
            // if child found, we are ok for parent<->child relation
            if ( $c == $child )
                return true;        
        
        return false;
    }
    
    
        
    /**
     * Add an item as an allowed child of this item
     *
     * @param Item $child
     */
    public function addChildItem(Item $child)
    {
        $this->childs[] = $child;
    }
    


    /**
     * Add items as a allowed childs of this item
     *
     * @param Item[] $childs
     */
    public function addChildItems(array $childs)
    {
        foreach ( $childs as $c )
            $this->addChildItem($c);
    }
    
    
}

?>
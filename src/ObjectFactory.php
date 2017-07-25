<?php

namespace Nettools\JsDocPhp;







/**
 * Factory for JSObjects
 */
class ObjectFactory {
    
    /**
     * Create a JS object from a keyword
     * 
     * @param JSObjects\Object $parent
     * @param string $k
     * @param string $code
     * @return JSObjects\Object
     */
    static public function fromKeyword($parent, $k, $code)
    {
        //['@class', '@function', '@method', '@param', '@return', '@property'];
        switch ( $k )
        {
            case '@class':
                return new JSObjects\ClassObject(null, trim($code));
            case '@namespace':
                return new JSObjects\NamespaceObject(null, trim($code));
            case '@method':
                return new JSObjects\Method(null, trim($code));
            case '@function':
                return new JSObjects\Func(null, trim($code));
            case '@property':
                $line = explode(' ', $code, 2);
                $o = new JSObjects\Property(null, trim($line[1]));
                $o->type = $line[0];
                return $o;
                
            case '@extends':
                return new JSObjects\ExtendsClass(null, trim($code));

            case '@param': 
                $line = explode(' ', $code, 3);
                $o = new JSObjects\Parameter(null, $line[1]);
                $o->type = $line[0];
                $o->shortDescription = $line[2];
                return $o;
                
            case '@throws': 
            case '@return':
                $line = explode(' ', $code, 2);
                if ( $k == '@throws' )
                    $o = new JSObjects\ThrowValue(null, null, trim($line[1]));
                else
                    $o = new JSObjects\ReturnValue(null, null, trim($line[1]));
                $o->type = $line[0];
                return $o;
                
            default:
                throw new Exceptions\Exception("Keyword '$k' not implemented in factory.");
        }
    }
  
}




?>
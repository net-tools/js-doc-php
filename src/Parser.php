<?php

namespace Nettools\JsDocPhp;







/**
 * Parser class
 */
class Parser {
    
    /**
     * JSdoc keywords  
     */
    const KEYWORDS = ['@package', '@class', '@function', '@method', '@namespace', '@param', '@return', '@extends', '@throws', '@property'];
    

    
    /**
     * Top level keywords (other keywords are single-line)
     */
    const TOPLEVEL_KEYWORDS = ['@package', '@class', '@function', '@method', '@property', '@namespace'];
    
    

    /**
     * Single-line keywords
     */
    const SINGLELINE_KEYWORDS = ['@param', '@return', '@throws', '@extends'];
    
    
    
    /**
     * Options
     *
     * @var string[]
     */
    protected $_options = array();
    
    
    
    /**
     * List of classes/namespaces documented (used to create links)
     *
     * @var JSObjects\ClassObject[]
     */
    public $projectClassesNamespaces = [];
    
    
    
    /** 
     * Grammar definition
     *
     * @var Grammar\Grammar
     */
    protected $_grammar = null;
    
    
    
    /**
     * Normalize object
     *
     * For example, methods without a return value are modified with a returnValue property equals to "void"
     *
     * @param JSObjects\JSObject $obj
     */
    protected function _normalize(JSObjects\JSObject $doc)
    {
        // detect function/methods with no return
        if ( $doc instanceof JSObjects\Func )
            if ( is_null($doc->returnValue) )
            {
                $doc->returnValue = new JSObjects\ReturnValue($doc, '');
                $doc->returnValue->type = 'void';
            }
    }
    
    
    
    /** 
     * Check grammar
     * 
     * @param JSObjects\JSObject $o
     * @param JSObjects\JSObject $parent
     * @param string $doc Current docblock, as a string
     * @return bool Returns True if grammar checking is ok, otherwise an exception is thrown
     * @throws Exceptions\ParserException
     */
    protected function _checkGrammar(JSObjects\JSObject $o, JSObjects\JSObject $parent, $doc)
    {
        try
        {
            return $this->_grammar->check($o, $parent);
        }
        catch(Exceptions\GrammarException $e)
        {
            throw new Exceptions\ParserException($e->getMessage(), $doc);
        }
    }
    
    
    
    /** 
     * Constructor
     *
     * @param string[] $options Options for parser
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
        $this->_grammar = Grammar\Grammar::build();
    }
    
    
    
    /**
     * Is strict mode on ?
     * 
     * @return bool
     */
    public function isStrict()
    {
        return (bool) $this->_options['strict'];
    }
    
    
    
    /** 
     * When a property/method is declared outside its parent block (static method/property), find a reference to its parent
     *
     * @param JSObjects\JSObject $o Orphaned object 
     * @param string $class Classname of the object parent
     * @param \Psr\Log\LoggerInterface $log Log interface to log warning in non-strict mode
     */
    public function findParentClassNamespace(JSObjects\JSObject $o, $class, \Psr\Log\LoggerInterface $log)
    {
        // looking for parent class
        foreach ( $this->projectClassesNamespaces as $cl )
        {
            if ( $cl->name == $class )
            {
                $cl->takeOwnership($o);
                break;
            }
        }

        // if no parent found that's an error
        if ( is_null($o->parent) )
        {
            $log->warning("Can't find the parent object '$class' for '{$o->name}'");
            if ( $this->isStrict() )
                throw new Exceptions\ParserException("Can't find the parent object '$class' for object", $o->name);
        }
    }
    
    
    
    /**
     * Parse a docblock and return info
     *
     * @param string $doc
     * @param \Psr\Log\LoggerInterface $log Log interface to log warning in non-strict mode
     * @return JSObjects\JSObject
     * @throws Exceptions\ParserException
     */
    public function parseDocBlock($doc, \Psr\Log\LoggerInterface $log)
    {
        $keywords = [];
        
        // look for keywords
        foreach ( self::KEYWORDS as $k )
        {
            $regs = [];
            $keywords[$k] = [];
            
            
            if ( preg_match_all("~\\* {$k}(.*)$~mU", $doc, $regs) && $regs[1] && is_array($regs[1]) )
            {
                foreach ( $regs[1] as $line )            // $regs[1] is an array of matching capturing parenthesis 
                {
                    $o = ObjectFactory::fromKeyword(null, $k, trim(preg_replace('/[\\t ]+[ ]/', ' ', $line)));
                    $keywords[$k][] = $o;
                }                
            }
                
        }

        
        // look for top level keywords and associate ; as soon as one is found, exiting loop as only one item is documented at a time
        $obj = null;
        foreach ( self::TOPLEVEL_KEYWORDS as $topk )
            if ( count($keywords[$topk]) )
            {
                $obj = $keywords[$topk][0];
                break;
            }
        
        
        // look for object being documented (either a class, a function, a method or a property)
        if ( !$obj )
        {
            $log->warning("Can't find docblock kind (class, function, method, namespace, property)", ['docblock'=>$doc]);
            if ( $this->isStrict() )
                throw new Exceptions\ParserException("Can't find docblock kind (class, function, method, namespace, property)", $doc);
            else
                return null;
        }

        if ( !$obj->name )
            throw new Exceptions\ParserException("Docblock has no name set", $doc);
        
        
        // for other keywords (single-line), they belong to either a class (constructor parameters), a function, a method
        // checking grammar, and if ok, we take ownership
        foreach ( self::SINGLELINE_KEYWORDS as $sk )
            foreach ( $keywords[$sk] as $o )
                if ( $this->_checkGrammar($o, $obj, $doc) )
                    $obj->takeOwnership($o);
        
        
        // remove lines with keywords
        $ndoc = preg_replace('~^[ \\t]*\\*[ ]@[a-z]+[ ](.*)$~mU', '', $doc);
        
        
        // get first line of comments ; since .* doesn't match newlines, we only get first line matching : short description
        if ( !preg_match('~^[ \\t]*\\*[ ](.*)~m', $ndoc, $regs) )
            $regs = array('', 'no description available');
            
        $obj->shortDescription = trim($regs[1]);
        
        
        // get long description, if available : there are empty lines before and after
        $firstline_with_short_description = '\\*\\*[\\r\\n\\t ]+\\*[^\\n\\r]+';
        $empty_line = '[\\r\\n]+[\\t ]+\\*[ ]*[\\r\\n]+';
        $long_description = '(.*)';
        if ( preg_match('~' . $firstline_with_short_description . $empty_line . $long_description . $empty_line . '~s', $ndoc, $regs) )
            $obj->description = trim(preg_replace('~^[\\t ]+\\*[\t ]?~m', "", $regs[1]));

        
        // normalize object
        $this->_normalize($obj);

        
        // returning top-level object documented
        return $obj;
    }
    
    
    
    
    /**
     * Parse a file and get a data structure about classes in the file
     *
     * @param string $root Root directory of project (eg. /home/dev/src/)
     * @param string $file File path (eg. includes/inc.js)
     * @param \Psr\Log\LoggerInterface $log Log interface to log warning in non-strict mode
     * @return JSObjects\Package
     * @throws Exceptions\Exception Exception thrown if an error occured
     */
    public function parse($root, $file, \Psr\Log\LoggerInterface $log)
    {
        $package = null;
		
		// processing file
		$log->info("Processing file '$file'");
        
        // read file
        $f = file_get_contents($root . $file);
    
        
   
        // look for all first level comments (/** at beginning of line, some stuff and then [ ]*/ at beginning of line)
        // m : ^ match begin of lines inside the string ; without, they match only a beginning of string
        // s : . matches newlines
        // U : ungreedy
        $regs = [];
        if ( preg_match_all('~^/\\*\\*.*^ \\*/~msU', $f, $regs) === FALSE )
            throw new Exceptions\Exception("Malformed docblock in '$file'");

        // we always have a $regs[0] value, which may be an empty array if no top-level docblock identified
        if ( count($regs[0]) == 0 )
        {
            $log->warning("No valid docblock in '$file'");
            if ( $this->isStrict() )
                throw new Exceptions\Exception("No valid docblock in '$file'");            
        }
        
                
        // look for all toplevel docblocks
        foreach ( $regs[0] as $docblock )
        {
            // parse this docblock ; if we get null, we are not in strict mode, and an error occured, and we ignore it
            $doc = $this->parseDocBlock($docblock, $log);
            if ( !$doc )
                continue;
            
            
            
            // if we have a package docblock, this must be the first ; otherwise, this is an error
            if ( $doc instanceof JSObjects\Package )
                if ( is_null($package) )
                {
                    $package = $doc;
                    $package->file = $file;
                    
                    // no need to go further, a package docblock has no content
                    continue;
                }
                else
                    throw new Exceptions\ParserException("Package docblock must be the first docblock in source code", $docblock);
            
            // if docblock is not a package, create the default package (if no package docblock) 
            else
                if ( is_null($package) )
                {
                    $package = new JSObjects\Package(null, $file);
                    $package->file = $file;
                }
            

            
            // handle classes and functions : the package takes their ownership
            if ( (get_class($doc) == JSObjects\ClassObject::class) || (get_class($doc) == JSObjects\Func::class) || (get_class($doc) == JSObjects\NamespaceObject::class) )
            {
                // grammar checking
                if ( $this->_checkGrammar($doc, $package, $docblock) )
                    $package->takeOwnership($doc);
            }
            
            // if this is not a class nor a function nor a namespace, this may be a prototype method or static method declared outside the class constructor {}
            else if ( $doc instanceof JSObjects\Method )
            {
                // the class the prototype/static method belongs to is mentionned in the name : "MyNs.MyClass.prototype.protoMethod" or "MyNs.Myclass.staticMethod"
                $fullname = $doc->name;
                $doc->name = substr(strrchr($fullname, '.'), 1);
                
                
                if ( is_int(strpos($fullname, '.prototype')) )
                {
                    $doc->staticMethod = false;
                    $doc->prototypeMethod = true;
                    $class = str_replace('.prototype.' . $doc->name, '', $fullname);
                }
                else
                {
                    $doc->staticMethod = true;
                    $doc->prototypeMethod = false;
                    $class = str_replace('.' . $doc->name, '', $fullname);
                }

                
                
                // looking for class with name = $class
                foreach ( $this->projectClassesNamespaces as $cl )
                    if ( $cl->name == $class )
                    {
                        if ( $this->_checkGrammar($doc, $cl, $docblock) )
                            $cl->takeOwnership($doc);
                        
                        break;
                    }
                
                
                // find parent
                if ( is_null($doc->parent) )
                    $this->findParentClassNamespace($doc, $class, $log);
            }
            
            
            // if this is not a class nor a function nor a namespace, this may be a static property declared outside the class constructor {}
            else if ( $doc instanceof JSObjects\Property )
            {
                // the class the static property belongs to is mentionned in the name : "MyNs.MyClass.protoProperty"
                $fullname = $doc->name;
                $doc->name = substr(strrchr($fullname, '.'), 1);
                $doc->staticProperty = true;
                $class = str_replace('.' . $doc->name, '', $fullname);

                // looking for class with name = $class
                foreach ( $this->projectClassesNamespaces as $cl )
                    if ( $cl->name == $class )
                    {
                        if ( $this->_checkGrammar($doc, $cl, $docblock) )
                            $cl->takeOwnership($doc);
                        
                        break;
                    }
                
                
                // find parent
                if ( is_null($doc->parent) )
                    $this->findParentClassNamespace($doc, $class, $log);
            }
            
            
            // no link to main docblock found
            else
            {
                $log->warning("Docblock can't be processed", ['docblock'=>$docblock]);
                if ( $this->isStrict() )
                    throw new Exceptions\ParserException("Docblock can't be processed", $docblock);
                else
                    continue;
            }
            
            
            
            // if orphaned and non strict mode, $doc->parent can be null here
            if ( is_null($doc->parent) )
                continue;
            
            
            // normalize object
            $this->_normalize($doc);
            
            
            
            // explore class/namespace content and register class in class map
            if ( $doc instanceof JSObjects\ClassObject || $doc instanceof JSObjects\NamespaceObject )
            {
                $this->projectClassesNamespaces[$doc->name] = $doc;

                
                // extract code inside class/namespace
                unset($regs);
                if ( preg_match('~' . str_replace(array('*', '.', '(', ')', '[', ']', '?', '+'), array('\\*', '\\.', '\\(', '\\)', '\\[', '\\]', '\\?', '\\+'), $docblock) . '[^{]+\\{(.*?)^\\}~ms', $f, $regs) )
                {
                    $classcontent = $regs[1];
                                    
                    unset($regs);
                    if ( preg_match_all('~[\\t ]+/\\*\\*(.*)[\\t ]+\\*/~sU', $classcontent, $regs) )
                    {
                        //print_r($regs);
                        // look for docblocks inside class/namespace
                        foreach ( $regs[0] as $docblk )
                        {
                            // parse this docblock
                            $innerdoc = $this->parseDocBlock($docblk, $log);
                            if ( $innerdoc && $this->_checkGrammar($innerdoc, $doc, $docblk) )
                                $doc->takeOwnership($innerdoc);
                        }
                    }
                }
                else
                    // if docblock cannot be extracted, we halt because the docblock is processed, but not its content, that's not coherent
                    throw new Exceptions\ParserException("Class/namespace content cannot be extracted for this docblock", $docblock);
            }
        }
        
        return $package;                    
    }
    
}




?>
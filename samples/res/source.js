/**
 * Class MyClass jsdoc
 *
 * This is a multiline comment
 * for class MyClass.
 *
 * @class MyClass
 * @param string param1 This is the first parameter
 * @param bool param2 This is the second parameter
 */
var MyClass = function (param1, param2) {
    /** 
     * Class method here
     *
     * More details about method here
     *
     * @method fun1
     * @param string p1 Parameter 1
     * @param string p2 Parameter 2
     * @return int
     */
    this.fun1 = function(p1, p2)
    {        
        return 10;
    }
    
    
    /**
     * This is property1
     *
     * This property has a multiline comment
     * - with an enum
     * - of values
     *
     * @property string property1
     */
    this.property1 = 'default value';
    
    
    /**
     * This is property2, which is null
     *
     * @property int property2
     */
    this.property2 = null;
}




/**
 * Namespace 'test'
 * 
 * @namespace ns.test
 */
var ns.test = {
    
    /**
     * This is a method from a namespace
     *
     * @method hello
     */
    hello : function()
    {
        alert('hello');
    }
}





/**
 * Class MyClass2 jsdoc
 *
 * @class MyClass2
 * @extends MyClass1
 * @param string param1 This is the first parameter
 * @param bool param2 This is the second parameter
 */
function MyClass2(param1, param2)
{
    /** 
     * Class method here
     *
     * More details about method here
     *
     * @method fun1
     * @param string p1 Parameter 1
     * @param string p2 Parameter 2
     * @return int
     * @throws Error If an error occurs, an Error exception is thrown
     */
    this.fun1 = function(p1, p2)
    {        
        return 10;
    }
    
    
    /**
     * This is property1
     *
     * @property string property1
     */
    this.property1 = 'default value';
    
    
    /**
     * This is property2, which is null
     *
     * @property int property2
     */
    this.property2 = null;
    
    
    /**
     * Object property of type MyClass
     *
     * @property MyClass property3
     */
    this.property3 = null;
}

/**
 * Static method defined
 *
 * @method MyClass2.staticMethod 
 * @param Object params Object litteral as method parameter 
 * @return string
 */
MyClass2.staticMethod = function(params)
{
    return "nothing here";
}


/**
 * Prototype method here
 *
 * @method MyClass2.prototype.protoMethod
 * @param MyClass obj Object litteral
 * @return bool
 */
MyClass2.prototype.protoMethod = function(obj)
{
    return false;
}





/**
 * A long description class
 *
 * Here is the long description 
 * for the class. 
 *
 * @class LongDescrClass
 */
class LongDescrClass{
    
}



/**
 * Function here
 *
 * Long description for function here
 *
 * @function testhere
 * @param string test This is the argument for function testhere()
 * @param function callback This is a callback
 * @return int A return value here
 */
function testhere(test, callback)
{
    return 0;
}



/**
 * Other function here
 *
 * @function testhereandthere
 * @throws Error If an error occurs, an Error exception is thrown
 */
function testhereandthere()
{
}
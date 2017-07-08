/**
 * Class MyClass jsdoc
 *
 * @class MyClass
 * @param string param1 This is the first parameter
 * @param bool param2 This is the second parameter
 */
function MyClass(param1, param2)
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
     */
    this.fun1 = function(p1, p2)
    {        
        return 10;
    }
}


/**
 * Static method defined
 *
 * @method MyClass.staticMethod 
 * @param Object params Object litteral as method parameter 
 * @return string
 */
MyClass.staticMethod = function(params)
{
    return "nothing here";
}


/**
 * Prototype method here
 *
 * @methode MyClass.prototype.protoMethod
 * @param Object obj Object litteral
 * @return bool
 */
MyClass.prototype.protoMethod = function(obj)
{
    return false;
}
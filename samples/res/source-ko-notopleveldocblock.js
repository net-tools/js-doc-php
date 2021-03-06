/*
 * Class MyClass jsdoc
 *
 * Here, we have omitted a star in the first line. The parser will throw a warning (or error if strict mode)
 *
 * @param string param1 This is the first parameter
 * @param bool param2 This is the second parameter
 */
var MyClass = function (param1, param2) {
 
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




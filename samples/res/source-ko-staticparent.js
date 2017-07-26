/**
 * Class MyClass jsdoc
 *
 * @class MyClass
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



/**
 * Static property
 * 
 * We have made a mistake by not linking to wrong parent class
 *
 * @property string MyOtherClass.staticProperty
 */
MyClass.staticProperty = "test property";



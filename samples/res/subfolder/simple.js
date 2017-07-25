
/**
 * Simple class in a subfolder
 * 
 * @class HelloWorld
 */
var HelloWorld = function(){
    
    /**
     * Simple method
     *
     * @method sayHello
     */     
    this.sayHello = function (){alert('Hello world !');};
    

    /**
     * Returning an object of MyClass
     *
     * @method createObject
     * @return MyClass
     */
    this.createObject = function(){return new MyClass('test', true)};
    
}
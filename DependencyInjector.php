<?php 

/**
 * Holds an array of services/closures and data
 * Provides magic method interface to set and retrive services/data
 * Services should be stored in closures
 * Function getService return lambada
 */
class DependencyInjector
{

    /**
     * @var holds an array of services and other values
     */
    private $services = [];

    /**
     * Create new instance
     */
    public function __construct(){
    }

    /**
     * Show what services and keys are available
     */
    public function listServices(){
        foreach ($this->services as $key => $value) {
            echo "$key<br>".PHP_EOL;
        }
    }

    /**
     * Get service
     * @param service name
     * @param mix closure/service or variable
     */
    public function setService($name, $service){
        $this->services[$name] = $service;
    }

    /**
     * Get service
     * @param service name
     */
    public function getService($name){
        try{
            if(array_key_exists($name, $this->services)){
                return $this->services[$name];
            }
            throw new \Exception("Service $name was not registered", 1);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Call function directly from a container. 
     * !important function has to take arguments in an array or none
     * us of this method require specyfic build of a closure
     * @param service name
     * @param arrey of arguments for that service
     */
    public function runService($name, Array $args = []){
        try{
            if(is_callable($this->services[$name])){
                if(array_key_exists($name, $this->services)){
                    if($args != [] && is_array($args)){
                        return $this->services[$name]($args);
                    }
                    else{
                        return $this->services[$name]();
                    }
                }
                throw new \Exception("Service $name was not registered<br>".PHP_EOL, 1);
            }else{
                throw new \Exception("Subject $name is not callable<br>".PHP_EOL, 1);
            }
            
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Get service directly calling on container method
     * @param service name $container->name()
     */
    public function __get($name){
        return $this->getService($name);
    }

    /**
     * Set service or variable
     * @param service name
     * @param closure, anonymus function or variable
     */
    public function __set($name, $value){
        $this->setService($name, $value);
    }

    /**
     * Prints class name
     * @param
     */
    public function __toString(){
        return "DependencyInjector ".PHP_EOL;
    }

}

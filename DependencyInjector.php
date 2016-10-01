<?php 

class DependencyInjector
{

    private $services = [];

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

    public function setService($name, $service){
        $this->services[$name] = $service;
    }

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

    public function __get($name){
        return $this->getService($name);
    }

    public function __set($name, $value){
        $this->setService($name, $value);
    }

    public function __toString(){
        return "DependencyInjector<br>".PHP_EOL;
    }

}

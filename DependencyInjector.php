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
            if(array_key_exists($name, $this->services)){
                if($args != [] && is_array($args)){
                    return $this->services[$name]($args);
                }
                else{
                    return $this->services[$name]();
                }
            }
            throw new \Exception("Service $name was not registered", 1);
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

/**
 * Use Example
 */
echo "<pre>";
$container = new DependencyInjector();

/**
 * Standard Use
 */
$test = 1;

$container->setService('db_config',['setup'=>'mysql:host=localhost;dbname=test;charset=utf8mb4', 'user'=>'root', 'password'=>'', 'settings'=>[PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]]);

$container->setService('db', function($a) use ($container){
    // function args as arrey
    echo "HELLO FROM INSIDE DB<br> {$a[0]} {$a[1]}<br>".PHP_EOL;

    // function
    $config = $container->getService('db_config');
    return new \PDO($config['setup'], $config['user'], $config['password'], $config['settings']);
});

$db1 = $container->getService('db');
$db1([1,2]);
var_dump($db1); // Object (Closure)

$db2 = $container->runService('db', [1,2]);
var_dump($db2); // Object

/**
 * Shortcuts
 */
// $container->db = function() use ($container){
//     echo "HELLO db<br>".PHP_EOL;
//     $config = $container->getService('db_config');
//     return new \PDO($config['setup'], 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
// };

// $db = $container->db;

/**
 * List all available services
 */
// $container->listServices();
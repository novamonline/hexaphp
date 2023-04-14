<?php 
namespace HexaPHP\Libs\Application;
use Exception;
use Closure;
use ReflectionClass;

/**
 * Handles dependency injection
 */

 class Container
{
    private $bindings = [];
    
    public function bind(string $abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }
    
    public function make(string $abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("No binding found for '{$abstract}'");
        }
        
        $concrete = $this->bindings[$abstract];
        
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }
        
        $reflector = new ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        
        if (!$constructor) {
            return new $concrete;
        }
        
        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);
        
        return $reflector->newInstanceArgs($dependencies);
    }
    
    private function resolveDependencies(array $parameters)
    {
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            $typeHint = $parameter->getType();
            
            if (!$typeHint || $typeHint->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Can't resolve parameter {$parameter->name} for non-class type");
                }
            } else {
                $className = $typeHint->getName();
                $dependencies[] = $this->make($className);
            }
        }
        
        return $dependencies;
    }
}

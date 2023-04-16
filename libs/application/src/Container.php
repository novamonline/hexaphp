<?php 
namespace HexaPHP\Libs\Application;

use Exception;
use Closure;
use ReflectionClass;
use Psr\Container\ContainerInterface;

/**
 * Handles dependency injection
 */
class Container implements ContainerInterface
{
    private $bindings = [];
    
    public function bind(string $abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }
    
    public function get(string $id): ?object
    {
        if (!isset($this->bindings[$id])) {
            throw new Exception("No binding found for '{$id}'");
        }
        
        $concrete = $this->bindings[$id];
        
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
    
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
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
                $dependencies[] = $this->get($className);
            }
        }
        
        return $dependencies;
    }
}

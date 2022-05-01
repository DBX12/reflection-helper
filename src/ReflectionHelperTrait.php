<?php

namespace dbx12\reflectionHelper;

use InvalidArgumentException;
use ReflectionClass;

trait ReflectionHelperTrait
{
    /**
     * Invokes an inaccessible method
     *
     * @param object|string $objectOrClassFqn Either an object (for non-static calls) or a FQN of a class (static calls)
     * @param string        $methodName       Name of the method to call
     * @param array         $args             Arguments to the called method
     * @return mixed
     * @throws \ReflectionException           if a class FQN is given but does not exist or the property could not be
     *                                        found in the given class (instance) or its ancestors
     */
    public function invokeMethod($objectOrClassFqn, string $methodName, array $args = [])
    {
        $info   = $this->processObjectOrClassFqn($objectOrClassFqn);
        $method = $info->reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($info->object, $args);
    }

    /**
     * Gets an inaccessible object property
     *
     * @param object|string $objectOrClassFqn Either an object (for non-static calls) or a FQN of a class (static calls)
     * @param string        $propertyName     Name of the property to get
     * @return mixed                          The value of the property
     * @throws \ReflectionException           if a class FQN is given but does not exist or the property could not be
     *                                        found in the given class (instance) or its ancestors
     */
    public function getInaccessibleProperty($objectOrClassFqn, string $propertyName)
    {
        $info     = $this->processObjectOrClassFqn($objectOrClassFqn);
        $property = $info->reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($info->object);
    }

    /**
     * Sets an inaccessible object property to a designated value
     *
     * @param object|string $objectOrClassFqn Either an object (for non-static calls) or a FQN of a class (static calls)
     * @param string        $propertyName     Name of the property to set
     * @param mixed         $value            New value of the property
     * @throws \ReflectionException           if a class FQN is given but does not exist or the property could not be
     *                                        found in the given class (instance) or its ancestors
     */
    public function setInaccessibleProperty($objectOrClassFqn, string $propertyName, $value): void
    {
        $info     = $this->processObjectOrClassFqn($objectOrClassFqn);
        $property = $info->reflection->getProperty($propertyName);
        $property->setAccessible(true);
        if ($info->object !== null) {
            // non-static property
            $property->setValue($info->object, $value);
        } else {
            // static property
            $property->setValue($value);
        }
    }

    /**
     * Processes a variable which either holds an object or a FQN for a class. Creates an instance of ReflectionClass
     * reflecting the given object or class.
     *
     * @param object|string $objectOrClassFqn Either an object (for non-static calls) or a FQN of a class (static calls)
     * @throws \ReflectionException if a class FQN is given but does not exist
     * @throws \InvalidArgumentException if the given argument is neither an object nor a string
     */
    private function processObjectOrClassFqn($objectOrClassFqn): ReflectionInfo
    {
        $info = new ReflectionInfo();
        if (is_object($objectOrClassFqn)) {
            $info->className = get_class($objectOrClassFqn);
            $info->object    = $objectOrClassFqn;
        } elseif (is_string($objectOrClassFqn)) {
            $info->className = $objectOrClassFqn;
        } else {
            throw new InvalidArgumentException(
                '$objectOrClass must be an object or FQN of a class, got ' . gettype($objectOrClassFqn)
            );
        }
        $info->reflection = new ReflectionClass($info->className);
        return $info;
    }
}

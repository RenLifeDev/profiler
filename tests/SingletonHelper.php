<?php

declare(strict_types=1);

namespace Renlife\Profiler\Tests;

class SingletonHelper
{
    /**
     * Clears Singleton class state.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @throws \ReflectionException
     */
    public static function clearState(string $className, string $propertyName = 'instance'): void
    {
        $reflectedClass = new \ReflectionClass($className);
        $prop           = $reflectedClass->getProperty($propertyName);

        $prop->setAccessible(true);
        $prop->setValue(null);
    }
}

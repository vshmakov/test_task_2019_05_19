<?php

declare(strict_types=1);

namespace App\Object;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class ObjectAccessor
{
    public static function initialize(string $class, array $data): object
    {
        $object = new  $class();
        self::setValues($object, $data);

        return $object;
    }

    private static function setValues(object $object, array $values): void
    {
        foreach ($values as $key => $value) {
            self::setValue($object, $key, $value);
        }
    }

    /**
     * @param mixed $value
     */
    private static function setValue(object $object, string $key, $value): void
    {
        $propertyAccessor = self::createPropertyAccessor();
        $propertyAccessor->setValue($object, $key, $value);
    }

    private static function createPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }
}

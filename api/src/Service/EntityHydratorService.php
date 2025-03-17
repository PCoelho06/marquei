<?php

namespace App\Service;

use ReflectionClass;
use ReflectionNamedType;
use BackedEnum;

class EntityHydratorService
{
    public function hydrate(object $entity, object $dto): object
    {
        $dtoProperties = get_object_vars($dto);
        $entityReflection = new ReflectionClass($entity);

        foreach ($dtoProperties as $property => $value) {
            $setter = 'set' . ucfirst($property);

            if (method_exists($entity, $setter) && $value !== null) {
                $propertyType = $this->getPropertyType($entityReflection, $property);

                // Si la propriété attend un Enum, on tente la conversion
                if ($propertyType && is_subclass_of($propertyType, BackedEnum::class)) {
                    $value = $propertyType::tryFrom($value);

                    if (!$value) {
                        throw new \InvalidArgumentException("Invalid value for enum $propertyType");
                    }
                }

                $entity->$setter($value);
            }
        }

        return $entity;
    }

    private function getPropertyType(ReflectionClass $entityReflection, string $property): ?string
    {
        $propertyReflection = $entityReflection->hasProperty($property)
            ? $entityReflection->getProperty($property)
            : null;

        if ($propertyReflection && $propertyReflection->getType() instanceof ReflectionNamedType) {
            return $propertyReflection->getType()->getName();
        }

        return null;
    }
}

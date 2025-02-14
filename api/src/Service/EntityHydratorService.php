<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

class EntityHydratorService
{
    public function hydrate(object $entity, object $dto): object
    {
        $dtoProperties = get_object_vars($dto);

        foreach ($dtoProperties as $property => $value) {
            $setter = 'set' . ucfirst($property);
            if (method_exists($entity, $setter) && $value !== null) {
                $entity->$setter($value);
            }
        }

        return $entity;
    }
}

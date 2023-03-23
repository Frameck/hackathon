<?php

namespace App\Traits;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

trait HasRelationships
{
    public function getRelationships()
    {
        $model = app(static::class);
        $reflectionClass = new ReflectionClass($model);

        $publicMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        $relationships = [];
        foreach ($publicMethods as $method) {
            $returnType = $method->getReturnType();

            if (
                $method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__ ||
                $method->isStatic() ||
                !$method->isUserDefined() ||
                blank($returnType) ||
                !$returnType instanceof ReflectionNamedType ||
                $returnType->isBuiltin()
            ) {
                continue;
            }

            $relation = (new ReflectionClass($returnType->getName()))->getParentClass();

            if (!$relation) {
                continue;
            }

            try {
                $return = $method->invoke($model);

                if ($return instanceof Relation) {
                    $relationships[$method->getName()] = [
                        'name' => $method->getName(),
                        'type' => (new ReflectionClass($return))->getShortName(),
                        'model' => (new ReflectionClass($return->getRelated()))->getName(),
                    ];
                }
            } catch(ErrorException $e) {
            }
        }

        return $relationships;
    }
}

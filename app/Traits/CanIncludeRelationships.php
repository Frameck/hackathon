<?php

namespace App\Traits;

trait CanIncludeRelationships
{
    protected function getRelationshipsToInclude(): array
    {
        $model = str(static::class)->classBasename()->beforeLast('Resource')->prepend('App\Models\\')->toString();
        $relationships = app($model)->getRelationships();

        $relationshipsCollections = [];
        foreach ($relationships as $relation) {
            $relationName = $relation['name'];
            $resourceNamespace = str('App\Http\Resources\\')
                ->append(
                    str($relationName)
                        ->singular()
                        ->ucfirst()
                )
                ->append('Resource')
                ->toString();

            if (!class_exists($resourceNamespace)) {
                continue;
            }

            if ($relation['type'] === 'BelongsTo') {
                $relationshipsCollections[$relationName] = $resourceNamespace::make($this->whenLoaded($relationName));

                continue;
            }

            $relationshipsCollections[$relationName] = $resourceNamespace::collection($this->whenLoaded($relationName));
        }

        return $relationshipsCollections;
    }
}

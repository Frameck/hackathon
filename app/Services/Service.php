<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Service
{
    protected static ?string $model = Model::class;

    public function __construct(Model|User|null $modelInstance)
    {
        $this->modelInstance = $modelInstance;
    }

    public static function make(Model|User|null $modelInstance = null): static
    {
        $static = app(static::class, ['modelInstance' => $modelInstance]);
        $static->setUp();

        return $static;
    }

    public function setUp(): void
    {
    }

    public static function getModelClass(): string
    {
        return static::$model;
    }

    public function getModelInstance(): Model|User
    {
        return $this->modelInstance;
    }
}

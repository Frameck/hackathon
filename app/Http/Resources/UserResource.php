<?php

namespace App\Http\Resources;

use App\Traits\CanIncludeRelationships;
use App\Traits\HasAttributesToExclude;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    use HasAttributesToExclude;
    use CanIncludeRelationships;

    public function toArray($request)
    {
        $attributes = Arr::except(
            parent::toArray($request),
            $this->getAttributesToExclude()
        );

        return array_merge(
            $attributes,
            $this->transformAttributes(),
            $this->getRelationshipsToInclude(),
        );
    }

    protected function getAdditionalAttributesToExclude(): array
    {
        return [
            'last_login',
            'ip',
            'user_agent',
            'email_verified_at',
        ];
    }

    protected function transformAttributes(): array
    {
        return [
            // 'file' => $this->transform($this->file, fn (string $filePath) => (
            //     asset('storage/' . $filePath)
            // ))
        ];
    }
}

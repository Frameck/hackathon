<?php

namespace App\Http\Requests;

use App\Services\UserService;
use App\Traits\FormRequestHelpers;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    use FormRequestHelpers;

    protected ?string $modelName = null;

    protected ?string $requestType = null;

    public function __construct()
    {
        $this->modelName = $this->getModelName();
        $this->requestType = $this->getRequestType();
    }

    public function authorize(): bool
    {
        return UserService::authorize(
            $this->requestType,
            "App\Models\\{$this->modelName}"::getResourceLabel()
        );
    }

    public function rules(): mixed
    {
        $serviceClass = app("App\Services\\{$this->modelName}Service");

        return $serviceClass::getValidationRules(
            mb_strtolower($this->requestType)
        );
    }
}

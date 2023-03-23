<?php

namespace App\Helpers;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationRuleParser;
use ReflectionClass;
use ReflectionFunction;

class PostmanHelper
{
    public static function getRoutesToExport(string $resource, string $path = 'api'): array
    {
        return RouteHelper::filterRoutes(
            RouteHelper::getRoutes(),
            path: "{$path}/{$resource}"
        );
    }

    public static function export(string $path = 'api'): bool
    {
        return static::exportCollection($path) &&
            static::exportEnvironment();
    }

    public static function exportCollection(string $path = 'api'): bool
    {
        return Storage::disk('local')
            ->put(
                'postman/' . config('app.name') . '.postman_collection.json',
                static::createJsonCollectionStructure($path)
            );
    }

    public static function exportEnvironment(): bool
    {
        return Storage::disk('local')
            ->put(
                'postman/' . config('app.name') . '.postman_environment.json',
                static::createJsonEnvironmentStructure()
            );
    }

    public static function createJsonCollectionStructure(string $path): string
    {
        $finalJson = static::initializeStructure($path);
        foreach (AppHelper::getModelsNames() as $key => $resource) {
            $resourceFolder = [
                'name' => str($resource)->plural()->ucsplit()->join(' '),
                'item' => [],
            ];

            $pluralSnakedCaseResource = str($resource)->snake()->plural()->toString();
            foreach (static::getRoutesToExport($pluralSnakedCaseResource, $path) as $route) {
                $resourceFolder['item'][] = static::createRouteJsonStructure($route);
            }

            if (blank($resourceFolder['item'])) {
                continue;
            }

            $finalJson['item'][] = $resourceFolder;
        }

        return json_encode(
            $finalJson,
            JSON_PRETTY_PRINT
        );
    }

    public static function createJsonEnvironmentStructure(): string
    {
        $environment = [
            'name' => config('app.name'),
            'values' => [
                [
                    'key' => 'BASE_URL',
                    'value' => config('app.url'),
                    'type' => 'default',
                    'enabled' => true,
                ],
                [
                    'key' => 'TOKEN',
                    'value' => '',
                    'type' => 'default',
                    'enabled' => true,
                ],
            ],
            '_postman_variable_scope' => 'environment',
        ];

        return json_encode(
            $environment,
            JSON_PRETTY_PRINT
        );
    }

    protected static function initializeStructure(string $path = 'api'): array
    {
        $structure = [
            'info' => [
                'name' => config('app.name'),
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'auth' => [
                'type' => 'bearer',
                'bearer' => [
                    [
                        'key' => 'token',
                        'value' => '{{TOKEN}}',
                        'type' => 'string',
                    ],
                ],
            ],
            'item' => [],
            'event' => [],
        ];

        if ($path == 'api') {
            $structure['item'][] = static::createLoginFolderAndRequest();
        }

        return $structure;
    }

    public static function getRouteHeaders(): array
    {
        return [
            [
                'key' => 'Accept',
                'value' => 'application/json',
                'type' => 'text',
            ],
        ];
    }

    public static function createLoginFolderAndRequest(): array
    {
        return [
            'name' => 'Auth',
            'item' => [
                [
                    'name' => 'Login',
                    'event' => [
                        [
                            'listen' => 'test',
                            'script' => [
                                'exec' => [
                                    'let token = pm.response.json().token;',
                                    '',
                                    'pm.environment.set(\'TOKEN\', token);',
                                ],
                            ],
                            'type' => 'text/javascript',
                        ],
                    ],
                    'request' => [
                        'auth' => [
                            'type' => 'noauth',
                        ],
                        'method' => 'POST',
                        'header' => static::getRouteHeaders(),
                        'body' => [
                            'mode' => 'formdata',
                            'formdata' => [
                                [
                                    'key' => 'email',
                                    'value' => User::getTestingUser()->email,
                                ],
                                [
                                    'key' => 'password',
                                    'value' => config('users.local.super_admin.password'),
                                ],
                            ],
                        ],
                        'url' => [
                            'raw' => '{{BASE_URL}}/api/login',
                            'host' => [
                                '{{BASE_URL}}',
                            ],
                            'path' => [
                                'api',
                                'login',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function createRouteJsonStructure(RoutingRoute $route): array
    {
        $uri = str($route->uri())->replaceMatches('/{([[:alnum:]]+)}/', ':$1');

        $method = Arr::first(
            array_filter(
                $route->methods(),
                fn (string $value) => in_array($value, [
                    'GET',
                    'POST',
                    'PATCH',
                    'DELETE',
                ])
            )
        );

        $data = [
            'name' => str($route->getActionName())->afterLast('@')->ucfirst()->toString(),
            'request' => [
                // fix for a known bug in laravel that doesn\'t parse form data if method is PATCH (https://github.com/laravel/framework/issues/13457)
                'method' => $method == 'PATCH' ? 'POST' : mb_strtoupper($method),
                'header' => static::getRouteHeaders(),
                'url' => [
                    'raw' => '{{BASE_URL}}/' . $uri,
                    'host' => [
                        '{{BASE_URL}}',
                    ],
                    'path' => $uri->explode('/')->filter()->toArray(),
                ],
            ],
        ];

        // $variables = $uri->matchAll('/(?<={)[[:alnum:]]+(?=})/m');
        $variables = $uri->matchAll('/:[a-z A-Z]+/m');
        if ($variables->isNotEmpty()) {
            $data['request']['url']['variable'][] = $variables->mapWithKeys(function ($variable) {
                $variable = str($variable)->after(':');

                return [
                    'key' => $variable,
                    'value' => 1,
                    'description' => $variable->append(' id')->toString(),
                ];
            })->all();
        }

        // if (in_array($method, ['POST', 'PATCH'])) {
        //     $emptyModelArray = array_combine(
        //         $emptyModelArray,
        //         // array_fill(0, count($emptyModelArray), '')
        //         $emptyModelArray
        //     );

        //     dd($emptyModelArray);

        //     $emptyModelArray = collect($emptyModelArray)
        //         ->combine($emptyModelArray)
        //         ->filter(function (string $key) {
        //             if (str($key)->is([
        //                 'slug',
        //                 '*_token',
        //                 'last_login',
        //                 'ip',
        //                 'user_agent',
        //                 'email_verified_at',
        //                 'created_by',
        //                 'updated_by',
        //                 'created_at',
        //                 'updated_at',
        //                 'deleted_at',
        //             ])) {
        //                 return false;
        //             }

        //             return true;
        //         })
        //         ->when(
        //             $method == 'POST',
        //             fn (Collection $emptyModelArray) => $emptyModelArray->forget('id'),
        //         );

        //         $emptyModelArray = $emptyModelArray->combine(
        //             array_fill(0, $emptyModelArray->count(), '')
        //         );

        //     $data['request']['body'] = [
        //         'mode' => 'raw',
        //         'options' => [
        //             'raw' => [
        //                 'language' => 'json'
        //             ]
        //         ],
        //         'raw' => json_encode(
        //             $emptyModelArray,
        //             JSON_PRETTY_PRINT
        //         )
        //     ];
        // }

        $validationRules = static::getRouteValidationRules($route);

        if (!empty($validationRules)) {
            if ($method == 'GET') {
                $data['request']['url']['query'] = static::parseValidationRulesIntoQueryParams($validationRules);
            } else {
                $data['request']['body'] = static::parseValidationRulesIntoFormData($validationRules, $method);
            }
        }

        return $data;
    }

    public static function getRouteValidationRules(RoutingRoute $route)
    {
        $requestRules = [];

        $routeAction = $route->getAction();

        $reflectionMethod = static::getReflectionMethod($routeAction);
        $rulesParameter = collect($reflectionMethod->getParameters())
            ->filter(function ($value, $key) {
                $value = $value->getType();

                return $value && is_subclass_of($value->getName(), FormRequest::class);
            })
            ->first();

        if ($rulesParameter) {
            $rulesParameter = $rulesParameter->getType()->getName();
            $rulesParameter = new $rulesParameter;
            $rules = method_exists($rulesParameter, 'rules') ? $rulesParameter->rules() : [];

            foreach ($rules as $fieldName => $rulesForField) {
                if (is_string($rulesForField)) {
                    $rulesForField = preg_split('/\s*\|\s*/', $rulesForField);
                }

                $printRules = true;

                $requestRules[] = [
                    'name' => $fieldName,
                    'description' => $printRules ? $rulesForField : '',
                ];

                if (is_array($rulesForField) && in_array('confirmed', $rulesForField)) {
                    $requestRules[] = [
                        'name' => $fieldName . '_confirmation',
                        'description' => $printRules ? $rulesForField : '',
                    ];
                }
            }
        }

        return $requestRules;
    }

    public static function parseValidationRulesIntoQueryParams(array $routeValidationRules): array
    {
        $queryParams = [];

        foreach ($routeValidationRules as $param) {
            $queryParam = [
                'key' => $param['name'],
                'value' => '',
                'type' => 'text',
                'description' => '',
                'disabled' => true,
            ];

            $queryParamsAsString = static::parseRulesIntoHumanReadable(
                $param['name'],
                $param['description']
            );

            $queryParam['description'] = $queryParamsAsString;

            if (str($queryParamsAsString)->contains('boolean')) {
                $queryParam['value'] = 1;
            }

            if (
                (str($queryParamsAsString)->contains('in:') && str($param['name'])->contains('.*')) ||
                (str($queryParamsAsString)->contains('exists:') && str($param['name'])->contains('.*'))
            ) {
                $alreadyPresent = Arr::first(
                    $queryParams,
                    fn ($v) => str($param['name'])->before('.*')->exactly($v['key'])
                );

                if ($alreadyPresent) {
                    $additionalValidationRules = $alreadyPresent['description'];
                    unset($queryParams[array_search($alreadyPresent, $queryParams)]);
                }

                $queryParam['key'] = str($param['name'])->before('.*')->append('[]')->toString();
                $queryParam['value'] = 1;
                $queryParam['description'] = $additionalValidationRules . '|' . $queryParam['description'];
            }

            $queryParams[] = $queryParam;
        }

        return array_values($queryParams);
    }

    public static function parseValidationRulesIntoFormData(array $routeValidationRules, string $routeMethod): array
    {
        $validationRules = [
            'mode' => 'formdata',
            'formdata' => [],
        ];

        if ($routeMethod == 'PATCH') {
            $validationRules['formdata'][] = [
                'key' => '_method',
                'value' => 'PATCH',
                'type' => 'text',
                'description' => 'fix for a known bug in laravel that doesn\'t parse form data if method is PATCH', // https://github.com/laravel/framework/issues/13457
                'disabled' => false,
            ];
        }

        foreach ($routeValidationRules as $rule) {
            $formData = [
                'key' => $rule['name'],
                'value' => '',
                'type' => 'text',
                'description' => '',
                'disabled' => false,
            ];

            $validationRulesAsString = static::parseRulesIntoHumanReadable(
                $rule['name'],
                $rule['description']
            );

            $formData['description'] = $validationRulesAsString;

            if (str($validationRulesAsString)->contains('sometimes')) {
                $formData['disabled'] = true;
            }

            $validationRules['formdata'][] = $formData;
        }

        return $validationRules;
    }

    protected static function parseRulesIntoHumanReadable($attribute, $rules): string
    {
        /*
         * An object based rule is presumably a Laravel default class based rule or one that implements the Illuminate
         * Rule interface. Lets try to safely access the string representation...
         */
        if (is_object($rules)) {
            $rules = [
                static::safelyStringifyClassBasedRule($rules),
            ];
        }

        /*
         * Handle string based rules (e.g. required|string|max:30)
         */
        if (is_array($rules)) {
            foreach ($rules as $i => $rule) {
                if (is_object($rule)) {
                    // unset($rules[$i]);
                    $rules[$i] = static::safelyStringifyClassBasedRule($rule);
                }
            }

            // $validator = Validator::make([], [
            //     $attribute => implode('|', $rules),
            // ]);

            // foreach ($rules as $rule) {
            //     [$rule, $parameters] = ValidationRuleParser::parse($rule);

            //     $validator->addFailure($attribute, $rule, $parameters);
            // }

            // $messages = $validator->getMessageBag()->toArray()[$attribute];

            // if (is_array($messages)) {
            //     $messages = static::handleEdgeCases($messages);
            // }

            // return implode(', ', is_array($messages) ? $messages : $messages->toArray());
        }

        return implode('|', $rules);
    }

    protected static function handleEdgeCases(array $messages): array
    {
        foreach ($messages as $key => $message) {
            if ($message === 'validation.nullable') {
                $messages[$key] = '(Nullable)';

                continue;
            }

            if ($message === 'validation.sometimes') {
                $messages[$key] = '(Optional)';
            }
        }

        return $messages;
    }

    protected static function safelyStringifyClassBasedRule(mixed $probableRule): string
    {
        if (!is_object($probableRule) || is_subclass_of($probableRule, Rule::class) || !method_exists($probableRule, '__toString')) {
            if ($probableRule instanceof Enum) {
                $enumClass = (new ReflectionClass($probableRule))->getProperty('type')->getValue($probableRule);
                $values = collect($enumClass::cases())
                    ->map(fn ($case) => (
                        $case->value
                    ))
                    ->implode(',');

                return 'in:' . $values;
            }

            return '';
        }

        if ($probableRule::class == 'Illuminate\Validation\Rules\In') {
            $probableRule = str((string) $probableRule)
                ->replace(['"', "'"], '');
        }

        return (string) $probableRule;
    }

    protected static function getReflectionMethod(array $routeAction): ?object
    {
        // Hydrates the closure if it is an instance of Opis\Closure\SerializableClosure
        if (static::containsSerializedClosure($routeAction)) {
            $routeAction['uses'] = unserialize($routeAction['uses'])->getClosure();
        }

        if ($routeAction['uses'] instanceof Closure) {
            return new ReflectionFunction($routeAction['uses']);
        }

        $routeData = explode('@', $routeAction['uses']);
        $reflection = new ReflectionClass($routeData[0]);

        if (!$reflection->hasMethod($routeData[1])) {
            return null;
        }

        return $reflection->getMethod($routeData[1]);
    }

    public static function containsSerializedClosure(array $action): bool
    {
        return is_string($action['uses']) && Str::startsWith($action['uses'], [
            'C:32:"Opis\\Closure\\SerializableClosure',
            'O:47:"Laravel\SerializableClosure\\SerializableClosure',
            'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure',
        ]);
    }
}

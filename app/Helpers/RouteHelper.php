<?php

namespace App\Helpers;

use App\Traits\HasMakeConstructor;
use Closure;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\RouteCollectionInterface;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionFunction;

class RouteHelper
{
    use HasMakeConstructor;

    public static function getRoutes(): RouteCollectionInterface
    {
        return Route::getRoutes();
    }

    public static function getRouteInformation(RoutingRoute $route): array
    {
        return [
            'domain' => $route->domain(),
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => ltrim($route->getActionName(), '\\'),
            'middleware' => static::getMiddleware($route),
            'vendor' => static::isVendorRoute($route),
        ];
    }

    public static function getMiddleware(RoutingRoute $route): string
    {
        return collect(
            app(Router::class)->gatherRouteMiddleware($route)
        )
            ->map(fn ($middleware) => (
                $middleware instanceof Closure ? 'Closure' : $middleware
            ))
            ->implode("\n");
    }

    public static function isVendorRoute(RoutingRoute $route): bool
    {
        if ($route->action['uses'] instanceof Closure) {
            $path = (new ReflectionFunction($route->action['uses']))->getFileName();
        } elseif (
            is_string($route->action['uses']) &&
            str($route->action['uses'])->contains('SerializableClosure')
        ) {
            return false;
        } elseif (is_string($route->action['uses'])) {
            if (static::isFrameworkController($route)) {
                return false;
            }

            $path = (new ReflectionClass($route->getControllerClass()))->getFileName();
        } else {
            return false;
        }

        return str($path)->startsWith(base_path('vendor'));
    }

    public static function isFrameworkController(RoutingRoute $route): bool
    {
        return in_array($route->getControllerClass(), [
            '\Illuminate\Routing\RedirectController',
            '\Illuminate\Routing\ViewController',
        ], true);
    }

    public static function filterRoutes(
        RouteCollectionInterface $routes,
        ?string $name = null,
        ?string $path = null,
        ?string $method = null,
        ?string $domain = null,
        bool $exceptPath = false,
        bool $exceptVendor = true,
        bool $onlyVendor = false,
    ): array {
        $filteredRoutes = [];
        foreach ($routes as $route) {
            $routeInformation = static::getRouteInformation($route);

            if (
                filled($name) && !str($routeInformation['name'])->contains($name) ||
                filled($path) && !str($routeInformation['uri'])->contains($path) ||
                filled($method) && !str($routeInformation['method'])->contains(mb_strtoupper($method)) ||
                filled($domain) && !str($routeInformation['domain'])->contains($domain) ||
                $exceptVendor && $routeInformation['vendor'] ||
                $onlyVendor && !$routeInformation['vendor']
            ) {
                continue;
            }

            if ($exceptPath) {
                foreach (explode(',', $exceptPath) as $path) {
                    if (str($routeInformation['uri'])->contains($path)) {
                        continue;
                    }
                }
            }

            $filteredRoutes[] = $route;
        }

        return $filteredRoutes;
    }

    public static function registerApiRoutes(): array
    {
        $resources = static::getResourcesWithRoutesToRegister();

        if (empty($resources)) {
            return [];
        }

        $routes = [];
        foreach ($resources as $resource => $endpoints) {
            $resource = str($resource);
            $resourceTitleCase = $resource->camel()->ucfirst()->toString();

            $routes[] = Route::apiResource(
                $resource->lower()->plural()->toString(),
                "App\Http\Controllers\Api\\{$resourceTitleCase}Controller"
            )->only($endpoints);
        }

        return $routes;
    }

    public static function getResourcesWithRoutesToRegister(): array
    {
        $routes = static::getApiControllerMethods();

        $resources = AppHelper::getSnakeCaseModelsNames();
        $resourcesToExclude = static::getResourcesToExclude();

        return collect($resources)
            ->mapWithKeys(function (string $resource) use ($routes, $resourcesToExclude) {
                $hasToBeExcluded = isset($resourcesToExclude[$resource]);

                if (!$hasToBeExcluded) {
                    return [
                        $resource => $routes,
                    ];
                }

                return [
                    $resource => array_diff(
                        $routes,
                        $resourcesToExclude[$resource]
                    ),
                ];
            })
            ->filter(fn (array $config) => !empty($config))
            ->toArray();
    }

    public static function getResourcesToExclude(): array
    {
        $routes = static::getApiControllerMethods();

        $modelsToExclude = config('routes.api.exclude');

        return collect($modelsToExclude)
            ->mapWithKeys(function (string|array $config, int|string $key) use ($routes) {
                if (is_array($config)) {
                    $key = str($key)
                        ->classBasename()
                        ->snake()
                        ->toString();

                    return [
                        $key => $config,
                    ];
                }

                $key = str($config)
                    ->classBasename()
                    ->snake()
                    ->toString();

                return [
                    $key => $routes,
                ];
            })
            ->toArray();
    }

    public static function getApiControllerMethods(): array
    {
        return [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ];
    }
}

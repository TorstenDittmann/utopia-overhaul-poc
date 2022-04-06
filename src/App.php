<?php

namespace UtopiaOverhaul;

use ReflectionAttribute;
use ReflectionClass;
use Utopia\Request;
use Utopia\Response;
use Utopia\Validator;

class App
{
    protected array $routes = [];

    public function register(string $controller): self
    {
        $reflector = new ReflectionClass($controller);
        $route = new Route($controller);

        $method = $reflector->getAttributes(Method::class)[0]->getArguments()[0];
        $path = $reflector->getAttributes(Route::class)[0]->getArguments()[0];

        $route
            ->setMethod($method)
            ->setPath($method);

        foreach ($reflector->getProperties() as $property) {
            if ($property->getAttributes(Param::class)) {
                $validator = $property->getAttributes(Validator::class, ReflectionAttribute::IS_INSTANCEOF);
                $validatorInstance = $validator ? $validator[0]->newInstance() : throw new \Exception("Validators are not optional.", 500);

                $optional = $property->getType()->allowsNull();

                $route->addParam(new Param(
                    key: $property->getName(),
                    validator: $validatorInstance,
                    optional: $optional,
                    default: $property->getDefaultValue() ?? '',
                ));
            }
        }
        if (!\array_key_exists($method, $this->routes)) {
            $this->routes[$method] = [];
        }

        if (!\array_key_exists($path, $this->routes[$method])) {
            $this->routes[$method][$path] = $route;
        } else {
            throw new \Exception("Route already exists!");
        }

        return $this;
    }

    public function start()
    {

    }

    public function run(Request $request, Response $response)
    {
        $method = $request->getMethod();
        $path = parse_url($request->getURI())['path'];
        try {
            if (!\array_key_exists($method, $this->routes) || !\array_key_exists($path, $this->routes[$method])) {
                throw new \Exception("Not Found", Response::STATUS_CODE_NOT_FOUND);
            }
            $this->routes[$method][$path]->run($request, $response);
        } catch (\Throwable $th) {
            $response->setStatusCode($th->getCode())->send($th->getMessage());
        }
    }
}

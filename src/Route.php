<?php

namespace UtopiaOverhaul;

use Utopia\Request;
use Utopia\Response;

class Route
{
    protected string $controller;
    protected string $path;
    protected string $method;
    protected array $params = [];
    protected array $resources = [];

    public function __construct(string $controller)
    {
        $this->controller = $controller;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function addParam(Param $param): self
    {
        $this->params[] = $param;

        return $this;
    }

    public function run(Request $request, Response $response): void
    {
        $class = new $this->controller();

        foreach ($this->params as $param) {
            /** @var Param $param */
            if (\is_null($request->getParam($param->key))) {
                if (!$param->optional) {
                    throw new \Exception("Parameter '{$param->key}' is not optional.", Response::STATUS_CODE_BAD_REQUEST);
                }
            } else {
                if (!$param->validator->isValid($request->getParam($param->key))) {
                    throw new \Exception($param->validator->getDescription(), Response::STATUS_CODE_BAD_REQUEST);
                }
                $class->{$param->key} = $request->getParam($param->key, $param->default);
            }
        }
        $class->action($request, $response);
    }
}

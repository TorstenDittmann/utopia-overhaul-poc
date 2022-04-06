<?php

namespace UtopiaOverhaul;

use Utopia\Request;
use Utopia\Response;

abstract class BaseController {
    public abstract function action(Request $request, Response $response): void;
}
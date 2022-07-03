<?php

namespace UtopiaOverhaul;

use Utopia\Request;
use Utopia\Response;

abstract class BaseController
{
    abstract public function action(Request $request, Response $response): void;
}

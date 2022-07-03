<?php

namespace UtopiaOverhaul;

use Utopia\Validator;

class Param
{
    public string $key;

    public string $default;

    public Validator $validator;

    public bool $optional = false;

    public function __construct(string $key, string $default, Validator $validator, bool $optional)
    {
        $this->key = $key;
        $this->default = $default;
        $this->validator = $validator;
        $this->optional = $optional;
    }
}

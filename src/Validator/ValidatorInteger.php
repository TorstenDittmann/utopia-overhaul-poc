<?php

namespace UtopiaOverhaul\Validator;

use Attribute;
use Utopia\Validator\Integer;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidatorInteger extends Integer {
}
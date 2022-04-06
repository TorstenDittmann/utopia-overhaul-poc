<?php

namespace UtopiaOverhaul\Validator;

use Attribute;
use Utopia\Validator\Text;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ValidatorText extends Text {
}
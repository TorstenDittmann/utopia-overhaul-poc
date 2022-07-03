<?php

namespace UtopiaOverhaul\Validator;

use Attribute;
use Utopia\Validator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Text extends Validator
{
    /**
     * @var int
     */
    protected int $length = 0;

    /**
     * Text constructor.
     *
     * Validate text with maximum length $length. Use $length = 0 for unlimited length
     *
     * @param  int  $length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * Get Description
     *
     * Returns validator description
     *
     * @return string
     */
    public function getDescription(): string
    {
        $message = 'Value must be a valid string';

        if ($this->length) {
            $message .= ' and no longer than '.$this->length.' chars';
        }

        return $message;
    }

    /**
     * Is array
     *
     * Function will return true if object is array.
     *
     * @return bool
     */
    public function isArray(): bool
    {
        return false;
    }

    /**
     * Get Type
     *
     * Returns validator type.
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_STRING;
    }

    /**
     * Is valid
     *
     * Validation will pass when $value is text with valid length.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        if (! \is_string($value)) {
            return false;
        }

        if (\mb_strlen($value) > $this->length && $this->length !== 0) {
            return false;
        }

        return true;
    }
}

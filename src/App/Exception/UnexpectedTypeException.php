<?php

namespace App\Exception;

/**
 * Exception thrown when a value doesn't have an expected type.
 */
class UnexpectedTypeException extends RuntimeException
{
    /**
     * The exception constructor.
     *
     * @param mixed           $value
     * @param string|string[] $type     The expected type or types.
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($value, $type, int $code = 0, \Throwable $previous = null)
    {
        $type = (array) $type;
        $message = sprintf(
            'Expected `%s`, got `%s`.',
            implode('|', $type),
            is_object($value) ? get_class($value) : gettype($value)
        );

        parent::__construct($message, $code, $previous);
    }
}

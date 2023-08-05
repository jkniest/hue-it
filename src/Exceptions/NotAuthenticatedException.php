<?php

namespace jkniest\HueIt\Exceptions;

use Throwable;

class NotAuthenticatedException extends HueItException
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Not authenticated', $code, $previous);
    }
}
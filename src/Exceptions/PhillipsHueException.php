<?php

declare(strict_types=1);

namespace jkniest\HueIt\Exceptions;

use Exception;
use Throwable;

class PhillipsHueException extends Exception
{
    public function __construct(string $message, mixed $code, ?Throwable $previous = null)
    {
        if (!is_int($code)) {
            $code = -1;
        }

        parent::__construct($message, $code, $previous);
    }
}

<?php

declare(strict_types=1);

namespace jkniest\HueIt\Exceptions;

use Exception;
use Throwable;

class PhillipsHueException extends Exception
{
    public function __construct(string $message, int $code, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

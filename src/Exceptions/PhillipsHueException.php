<?php

declare(strict_types=1);

namespace jkniest\HueIt\Exceptions;

use Exception;
use Throwable;

class PhillipsHueException extends Exception
{
    /**
     * @param int|mixed $code
     */
    public function __construct(string $message, $code, ?Throwable $previous = null)
    {
        if (!is_int($code)) {
            $code = -1;
        }

        parent::__construct($message, $code, $previous);
    }
}

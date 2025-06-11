<?php

declare(strict_types=1);

namespace App\Domain\Exception;

final class UserAlreadyExistsException extends \Exception
{
    public function __construct(string $message = 'User already exists', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

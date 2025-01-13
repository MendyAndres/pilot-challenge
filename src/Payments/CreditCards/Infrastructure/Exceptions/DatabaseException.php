<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Infrastructure\Exceptions;

final class DatabaseException extends \RuntimeException
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Database error: %s', $message),
            $code,
            $previous
        );
    }
} 
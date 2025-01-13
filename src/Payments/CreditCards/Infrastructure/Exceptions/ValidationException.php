<?php

declare(strict_types=1);

namespace App\Payments\CreditCards\Infrastructure\Exceptions;

final class ValidationException extends \RuntimeException
{
    private array $errors;

    public function __construct(array $errors, string $message = 'Validation failed')
    {
        $this->errors = $errors;
        parent::__construct($message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function fromArray(array $errors): self
    {
        return new self($errors);
    }

    public static function withMessage(string $message): self
    {
        return new self([], $message);
    }
} 
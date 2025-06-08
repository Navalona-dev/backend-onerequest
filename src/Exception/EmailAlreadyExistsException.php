<?php
namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmailAlreadyExistsException extends BadRequestHttpException
{
    private array $errors;

    public function __construct(string $message = 'Un compte avec cet email existe déjà.', array $errors = [], \Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $previous, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

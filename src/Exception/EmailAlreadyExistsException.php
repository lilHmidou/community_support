<?php

namespace App\Exception;

class EmailAlreadyExistsException extends \RuntimeException
{
    public function __construct(string $message = "L'adresse e-mail existe déjà.")
    {
        parent::__construct($message);
    }
}
<?php

namespace App\Exception;

class PasswordMismatchException extends \RuntimeException
{
    public function __construct(string $message = "Les mots de passe ne sont pas identiques ! Veuillez réessayer.")
    {
        parent::__construct($message);
    }
}
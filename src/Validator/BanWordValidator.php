<?php

namespace App\Validator;

use App\Validator\BanWord;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof BanWord) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            $this->context->buildViolation('Valeur attendue comme une chaîne de caractères')
                ->addViolation();
            return;
        }

        foreach ($constraint->bannedWords as $word) {
            if (false !== stripos($value, $word)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->addViolation();
                return;
            }
        }
    }
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
    public string $message = 'Le texte contient des mots interdits.';
    // Liste prédéfinie des mots interdits
    public array $bannedWords = [
        'injure',
        'insulte',
        'spam',
        'xxx',
        'faux',
        'haine'
    ];

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (isset($options['bannedWords']) && is_array($options['bannedWords'])) {
            $this->bannedWords = array_merge($this->bannedWords, $options['bannedWords']);
        }
    }
}

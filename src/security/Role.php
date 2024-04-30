<?php
// src/Security/Role.php
namespace App\security;

use MyCLabs\Enum\Enum;

/**
 * @method static Role ROLE_USER()
 * @method static Role ROLE_ADMIN()
 * @method static Role ROLE_ETUDIANT()
 * @method static Role ROLE_MENTOR()
 */
class Role extends Enum
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_ETUDIANT = 'ROLE_ETUDIANT';
    public const ROLE_MENTOR = 'ROLE_MENTOR';
}

?>
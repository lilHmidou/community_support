<?php

namespace App\Service\UserService;

interface UserServiceInterface
{
    public function isLogin(): bool;

    public function getUser();
}
<?php

namespace App\Service\ContactService;

use App\Entity\ContactMessage;

interface ContactServiceInterface
{
    public function saveContactMessage(ContactMessage $message):void;
}
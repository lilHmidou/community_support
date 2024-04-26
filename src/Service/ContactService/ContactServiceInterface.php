<?php

namespace App\Service\ContactService;

use App\Entity\ContactMessage;

interface ContactServiceInterface
{
    public function createContactMessage(): ContactMessage;
    public function saveContactMessage(ContactMessage $message):void;
}
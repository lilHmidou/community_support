<?php

// src/Service/FAQService.php
namespace App\Service;

use App\Repository\FAQRepository;

class FAQService
{
    private FAQRepository $faqRepository;

    public function __construct(FAQRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function getAllFAQs()
    {
        return $this->faqRepository->findAll();
    }
}

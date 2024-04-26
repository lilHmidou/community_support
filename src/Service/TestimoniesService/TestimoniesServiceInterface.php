<?php

namespace App\Service\TestimoniesService;

use App\Entity\Testimonies;

interface TestimoniesServiceInterface
{
    public function addTestimony(Testimonies $testimony): void;
    public function getAllTestimonies();
}
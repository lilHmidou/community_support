<?php

namespace App\Service\TutoratService\ProgramService\ProgramFormService;

use App\Entity\Program;
use App\Form\ProgramType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProgramFormServiceImpl implements ProgramFormServiceInterface
{
    private FormFactoryInterface $formFactory;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function createProgramForm(Request $request, ?Program $program = null): FormInterface
    {
        $program = $program ?? new Program();
        $form = $this->formFactory->create(ProgramType::class, $program);
        $form->handleRequest($request);
        return $form;
    }

    public function handleProgramFormSubmission(FormInterface $form): array
    {
        $program = $form->getData();

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        return ['status' => 'success', 'message' => 'Programme créé avec succès'];
    }
}
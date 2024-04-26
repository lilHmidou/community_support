<?php

namespace App\Service\TutoratService;

use App\Entity\Etudiant;
use App\Entity\Mentor;
use App\Entity\User;
use App\Form\EtudiantType;
use App\Form\MentorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TutoratServiceImpl implements TutoratServiceInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createFormBasedOnRole(FormFactoryInterface $formFactory, User $user): FormInterface
    {
        $userTutorat = $user->getUserTutorat();

        if (in_array('ROLE_MENTOR', $user->getRoles())) {
            return $this->formFactory->create(MentorType::class, $userTutorat);
        } elseif (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            return $this->formFactory->create(EtudiantType::class, $userTutorat);
        }

        throw new \LogicException('Aucun formulaire n\'a pu être créé pour cet utilisateur.');
    }

    public function deleteUserAndProgramsBasedOnRole(EntityManagerInterface $entityManager, User $user): void
    {
        $userTutorat = $user->getUserTutorat();

        if (in_array('ROLE_MENTOR', $user->getRoles())) {
            $mentor = $entityManager->getRepository(Mentor::class)->find($userTutorat->getId());

            foreach ($mentor->getMentorPrograms() as $program) {
                $entityManager->remove($program);
            }

            $entityManager->remove($mentor);
            $user->removeRole('ROLE_MENTOR');
        } elseif (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            $etudiant = $entityManager->getRepository(Etudiant::class)->find($userTutorat->getId());

            foreach ($etudiant->getEtudiantPrograms() as $program) {
                $entityManager->remove($program);
            }

            $entityManager->remove($etudiant);
            $user->removeRole('ROLE_ETUDIANT');
        }

        $entityManager->remove($userTutorat);
        $entityManager->flush();
    }
}
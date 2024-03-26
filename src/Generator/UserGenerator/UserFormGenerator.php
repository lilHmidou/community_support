<?php
namespace App\Generator\UserGenerator;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserFormGenerator
{
    private $formFactory;
    private $authenticationUtils;

    public function __construct(FormFactoryInterface $formFactory, AuthenticationUtils $authenticationUtils)
    {
        $this->formFactory = $formFactory;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function createRegistrationForm(User $user = null) : \Symfony\Component\Form\FormInterface
    {
        $user = $user ?? new User();
        $form = $this->formFactory->create(RegistrationFormType::class, $user);
        return $form;
    }

    public function prepareUserForm(): array
    {
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $isCheckboxChecked= false;

        $data = [
            'last_username' => $lastUsername,
            'isCheckboxChecked' => $isCheckboxChecked,
        ];

        return $data;
    }

    public function getErrors(): ?\Symfony\Component\Security\Core\Exception\AuthenticationException
    {
        return $this->authenticationUtils->getLastAuthenticationError();
    }
}



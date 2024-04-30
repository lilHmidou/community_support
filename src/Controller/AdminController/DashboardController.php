<?php

namespace App\Controller\AdminController;

use App\Entity\User;
use App\Entity\UserTutorat;
use App\Entity\Etudiant;
use App\Entity\Mentor;
use App\Entity\Post;
use App\Entity\Testimonies;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Route d'accès principal pour l'administration.
     *
     * @return Response
     */
    #[Route('/admin/handler', name: 'handler')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    /**
     * Configuration du tableau de bord EasyAdmin.
     *
     * @return iterable
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Community Support');
    }

    /**
     * Configuration du menu d'administration.
     *
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('UserTutorat', 'fas fa-user-tie', UserTutorat::class);
        yield MenuItem::linkToCrud('Etudiant', 'fas fa-user-graduate', Etudiant::class);
        yield MenuItem::linkToCrud('Mentor', 'fas fa-chalkboard-teacher', Mentor::class);
        yield MenuItem::linkToCrud('Post', 'fas fa-file-alt', Post::class);
        yield MenuItem::linkToCrud('Testimonies', 'fas fa-comment', Testimonies::class);
    }
}
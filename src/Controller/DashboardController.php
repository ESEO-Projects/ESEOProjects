<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

/**
 * Lorsqu'un utilisateur se connecte, il a accès à son dashboard.
 * @IsGranted("ROLE_STUDENT")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @return vue Liste des projets de l'utilisateur courant.
     */
    public function index(ProjectRepository $projectRepository, UserRepository $userRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('dashboard/index.html.twig', [
              'projects' => $projectRepository->findAll(),
              'users' => count($userRepository->findAll()),
              'views' => $projectRepository->getProjectsViews(),
              'not_enabled' => $userRepository->findBy(['enabled' => false])
            ]);
        }
        else{
            return $this->render('dashboard/index.html.twig', [
              'projects' => $this->getUser()->getProjects(),
            ]);
        }
    }

    /**
     * [projects description]
     * @param  ProjectRepository $projectRepository [description]
     * @return Response                             [description]
     * @IsGranted("ROLE_ADMIN")
     * @Route("/dashboard/projects", name="dashboard_projects")
     */
    public function projects(ProjectRepository $projectRepository): Response
    {
        return $this->render('dashboard/projects.html.twig', [
          'projects' => $projectRepository->findAll()
        ]);
    }
}

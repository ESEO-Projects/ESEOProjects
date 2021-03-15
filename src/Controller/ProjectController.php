<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Entity\IpRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * Liste de tous les projets de la plateforme.
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->redirectToRoute('app_home');
    }

    /**
     * Un nouveau projet peut être créé par un étudiant.
     * @Route("/new", name="project_new", methods={"GET","POST"})
     * @IsGranted("ROLE_STUDENT")
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $project->addUser($this->getUser());
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail_file')->getData();

            if($thumbnailFile){
              $project->setThumbnail(base64_encode(file_get_contents($thumbnailFile->getPathname())));
            }

            $project->setViews(0);
            $project->addUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project, Request $request): Response
    {
        // A chaque vue d'un projet, on incrémente de 1 dans la base de données le nombre de vues après vérification:
        $lastRequest = $this->getDoctrine()->getRepository(IpRequest::class)->getLastIpByPath($request->getPathInfo(), $request->getClientIp());
        $ipRequest = new IpRequest();
        $ipRequest->setTimestamp(new \Datetime());
        $ipRequest->setIp($request->getClientIp());
        $ipRequest->setPath($request->getPathInfo());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ipRequest);
        $entityManager->flush();
        if($lastRequest != null && ($lastRequest->getTimestamp()->modify('+5 minutes') < new \DateTime())){
          $project->setViews($project->getViews()+1);
        }

        $this->getDoctrine()->getManager()->flush(); // On update les données.
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="project_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_STUDENT")
     */
    public function edit(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('PROJECT_EDIT', $project);
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thumbnailFile = $form->get('thumbnail_file')->getData();

            if($thumbnailFile){
              $project->setThumbnail(base64_encode(file_get_contents($thumbnailFile->getPathname())));
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="project_delete", methods={"DELETE"})
     * @IsGranted("ROLE_STUDENT")
     */
    public function delete(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted('PROJECT_DELETE', $project);
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * [search description]
     * @param  [type]   $search [description]
     * @return Response         [description]
     * @Route("/search/", name="project_search", methods={"GET"})
     */
    public function search(ProjectRepository $projectRepository, Request $request): Response
    {
        $search = $request->query->get('search_project')["name"];
        $projects = $projectRepository->search($search);


        return $this->render('project/search.html.twig', [
          'projects' => $projects,
          'search' => $search
        ]);
    }
}

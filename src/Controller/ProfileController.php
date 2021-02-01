<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_index")
     */
    public function index(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, ['show_roles' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('profile/index.html.twig', [
            'user' =>$user,
            'form' => $form->createView(),
        ]);
    }
}

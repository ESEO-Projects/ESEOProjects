<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
  /**
   * @Route("/register", name="register", methods={"GET","POST"})
   *
   */
  public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
  {
      $user = new User();
      $form = $this->createForm(CreateUserType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $plainPassword = $form->get('plainPassword')->getData();

          $user->setPassword(
              $passwordEncoder->encodePassword(
                  $user,
                  $plainPassword
              )
          );
          $user->setRoles(["ROLE_STUDENT"]);

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($user);
          $entityManager->flush();

          return $this->redirectToRoute('user_index');
      }

      return $this->render('user/new.html.twig', [
          'user' => $user,
          'form' => $form->createView(),
      ]);
  }
}

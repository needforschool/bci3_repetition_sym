<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
          return $this->render('security/login.html.twig', [
              'last_username' => $lastUsername,
              'error'         => $error,
          ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }



    #[Route('/register', name: 'app_register')]
    public function register(Request $request, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $user = new User();

    $form = $this->createForm(UserRegisterType::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        //$em = $this->getDoctrine()->getManager();
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $em->persist($user);
        $em->flush();
        $this->addFlash('success','Bienvenue sur notre site');
        return $this->redirectToRoute('app_login');
    }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

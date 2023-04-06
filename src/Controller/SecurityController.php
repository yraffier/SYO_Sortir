<?php

namespace App\Controller;

use App\Entity\Campus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login_sortir')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        EntityManagerInterface $entityManager
    ): Response
    {
        $campus = $entityManager->getRepository(Campus::class)->findAll();
        if ($this->getUser()) {

            return $this->redirectToRoute('sortie_lister', compact('campus'));
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'campus' => $campus]);
    }

    #[Route(path: '/logout', name: 'logout_sortir')]
    public function logout(): void
    {

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

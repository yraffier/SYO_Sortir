<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'accueil_sortir')]
    public function index(): Response
    {
        return $this->render('utilisateur/accueil.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/', name: 'CompteUtilisateur_sortir')]
    public function compteUtilisateur(): Response
    {

        return $this->render('sortie/accueilUtilisateur.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

}

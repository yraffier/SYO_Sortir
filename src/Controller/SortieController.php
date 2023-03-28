<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// préfixe des routes pour les differentes méthodes concernant les sorties
#[Route ('/sortie', name : 'sortie')]
// accès à la page des sorties uniquement lorsque le user est connectée
//#[IsGranted( 'ROLE_USER')]

class SortieController extends AbstractController
{
    #[Route('/', name: 'sortie_lister')]
    public function lister(
        SortieRepository $sortieRepository
    ): Response
    // Méthodes pour récupérer l'ensemble des sorties
    {
        $sorties= $sortieRepository->findAll();
        return $this->render('sortie/lister.html.twig', [compact('sorties')
       ]);


    }
}

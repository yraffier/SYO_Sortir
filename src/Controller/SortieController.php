<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
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
    #[Route('/', name: '_lister')]
    public function lister(
        SortieRepository $sortieRepository
    ): Response
    // Méthodes pour récupérer l'ensemble des sorties
    {
        $sorties= $sortieRepository->findAll();
        return $this->render('sortie/accueilUtilisateur.html.twig', compact('sorties')
       );
    }
    /*
     * Méthode pour se descinscrir d'une sortie
     */
    #[Route('/sedesincrire/{sortie}', name: '_sedesinscrire')]
    public function sedesinscrire(
        Sortie $sortie,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository
    ): Response
    {
       try{
        $entityManager->remove($sortie);
        $entityManager->flush();
        $this->addFlash('succes','Vous êtes bien déscincrits de la sortie');
        return $this->redirectToRoute('sortie_lister');
       }catch(\Exception $exception) {
           $this->addFlash('echec', 'Vous n\'êtes pas désincrits de la sortie');
           return $this->redirectToRoute('sortie_lister');
           }

    }
    /*
     * Méthode pour afficher le déatails d'un sortie avec l'id de la sortie
     */
    #[Route('/detail/{sortie}', name: '_detail')]
    public function detail(
        Sortie  $sortie,
        SortieRepository $sortieRepository
    ): Response
       {
        if(!$sortie){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
               }
        return $this->render('sortie/detail.html.twig', compact('sortie'));
       }
/*
 * Méthode pour ajouter une nouvelle sortie
 */
//    #[Route('/ajouter',
//        name: '_ajouter',
//    )]
//    public function ajouter()
//
//
//
}

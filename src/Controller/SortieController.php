<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AjouterSortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/ajouter',
        name: '_ajouter',
    )]
    public function ajouter(
        Request $request,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager,


        // WishRepository $wishRepository
    ): Response
    {
        // créer une instance de sortie
        $sortie = new Sortie();
        // Creation d'un formulaire pour l'ajout d'une nouvelle sortie
        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        // Si le formulaire et valide et qu'on valide le formulaire

//        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
//            try {
//                $sortieForm->setNom();
//                $sortieForm->setDateHeureDebut();
//                $sortieForm->setduree();
//                $sortieForm->setDateHeureDebut();
//
//
//            } catch (\Exception $exception) {
//
//            }
//
//        }
        return $this->render('sortie/ajouter.html.twig', compact('sortieForm'));
    }

//
//        // $utilisateurCo= $userRepository->findOneBy(['username'=>$this->getUser()->getUsername()]);
//        // $wish->setAuthor($utilisateurCo->getUsername());
//        // $wish->setAuthor( $this->getUser()->getUserIdentifier());
//        // creation d'un nouveau formulaire
//        $wishForm = $this->createForm(WishType::class, $wish);
//        // prend la valeur des input et il complète $wish
//        $wishForm->handleRequest($request);
//
//        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
//            try {
//                $wish->setDateCreated(new \DateTime());
//                $wish->setIsPublished(true);
//                $wish->setUser($this->getUser());
//                $description = $wish->getDescription();
//                $descriptionpur = $censurator->purify($description);
//                $wish->setDescription($descriptionpur);
//                $titre = $wish->getTitle();
//                $titrepur = $censurator->purify($titre);
//                $wish->setTitle($titrepur);
//                $entityManager->persist($wish);
//                $entityManager->flush();
//                $this->addFlash('succes', 'Le souhait a bien été inséré');
//
//                return $this->redirectToRoute('wish_lister');
//                // return $this->redirectToRoute('wish_detailler', ["id" => $wish->getId()]);
//            } catch (\Exception $exception) {
//                $this->addFlash('echec', 'le souhait n\'a pas été inséré');
//
//                return $this->redirectToRoute('wish_ajouter');
//            }
//            // return $this->redirectToRoute('wish_lister',[],Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('wish/ajouter.html.twig', compact('wishForm'));



}

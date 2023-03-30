<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AjouterSortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
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

        return $this->render('sortie/accueilUtilisateur.html.twig', compact('sorties'));
    }



    /*
     * Méthode pour se descinscrir d'une sortie
     */
    #[Route('/sedesincrire/{sortie}', name: '_sedesinscrire')]
    public function sedesinscrire(
        Sortie $sortie,
        EntityManagerInterface $entityManager,

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

    #[Route('/ajouter', name: '_ajouter')]
        public function ajouter(
        Request $request,
        EtatRepository    $etatRepository,
        EntityManagerInterface $entityManager,
    )
    :Response
    {
        $sortie= new Sortie();
        $sortieForm =$this->createForm(AjouterSortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        try {

                if ($sortieForm->isSubmitted()&& $sortieForm-> isValid()) {

                    if($request->request->has('Enregistrer')) {
                        $etat = $etatRepository->find(292);
                        $sortie->setEtat($etat);
                        $this->addFlash('succes', 'CA va marcher');

                    }
                    elseif ($request->request->has('Ajouter')){
                        $etat = $etatRepository->find(288);
                        $sortie->setEtat($etat);
                        $this->addFlash('succes', 'Youpiiii');
                    }
                $sortie->setOrganisateurs($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('succes','Youhou vous avez crée une nouvelle sortie');
                return $this->redirectToRoute('sortie_lister');
            }

        }catch (\Exception $exception){
            $this->addFlash('echec', 'La sortie n\'a  pas été insérée');
             return $this->redirectToRoute('inscription_sortir');
        }
        return $this->render('sortie/ajouter.html.twig', compact('sortieForm'));



    }
//
//    #[Route('/enregistrer', name: '_enregistrer')]
//    public function enregistrer(
//        EtatRepository    $etatRepository,
//        Request $request,
//        EntityManagerInterface $entityManager
//    )
//    :Response
//    {
//        $sortie= new Sortie();
//        $sortieForm =$this->createForm(AjouterSortieType::class, $sortie);
//        $sortieForm->handleRequest($request);
//
//        try {
//            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
////                $data=$sortieForm->getData();
////                $date =$data['dateHeureDebut'];
//               // $sortie->setDateHeureDebut($sortie->getDateHeureDebut()->format('Y-m-d H:i:s'));
//                $etat = $etatRepository->find(2);
//                $sortie->setEtat($etat);
//
//                $entityManager->persist($sortie);
//                $entityManager->flush($sortie);
//                $this->addFlash('succes','Youhou vous avez crée une nouvelle sortie qui a été enregistré');
//                return $this->redirectToRoute('sortie_lister');
//            }
//        }catch (\Exception $exception){
//            $this->addFlash('echec', 'La sortie n\'a  pas été enregistré');
//            return $this->redirectToRoute('sortie_ajouter');
//        }
//        return $this->render('sortie/ajouter.html.twig', compact('sortieForm'));
//
//
//
//    }

}

<?php

namespace App\Controller;

use App\Entity\SearchData;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AjouterSortieType;
use App\Form\SearchType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        SortieRepository $sortieRepository,
        Request $request
    ): Response
    // Méthodes pour récupérer l'ensemble des sorties
    {
        $utilisateur = $this->getUser();
//        $sorties= $sortieRepository->findAll();
        $sorties = $sortieRepository->RechercherToutesLesSorties();
        $data = new SearchData();
        $searchForm = $this->createForm(SearchType::class, $data);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $sorties = $sortieRepository->findSearch($data, $utilisateur);
        }



        return $this->render('sortie/accueilUtilisateur.html.twig', compact(
            'searchForm',
            'sorties'
        ));
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

//    dd($sortie);
        try {

                if ($sortieForm->isSubmitted()&& $sortieForm-> isValid()) {

                    if($request->request->has('Enregistrer')) {
                        $etat = $etatRepository->find(308);
                        $sortie->setEtat($etat);
                        $this->addFlash('succes', 'CA va marcher');

                    }
                    elseif ($request->request->has('Ajouter')){
                        $etat = $etatRepository->find(305);
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
    #[Route('/lieurecuperer/{ville}', name: '_ajouterLieurecuperer')]
    public function lieurecuperer(
        Ville $ville,
        VilleRepository $villeRepository)
        : Response
    {

        $lieux = $ville->getLieux();
        $tableauDeReponses = array();

        foreach($lieux as $lieu){
            $tableauDeReponses[] = array(
                "id" => $lieu->getId(),
                "nom" =>$lieu->getNom()
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($tableauDeReponses);
    }



}

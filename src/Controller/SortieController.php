<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\SearchData;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AjouterSortieType;
use App\Form\AnnulerMaSortieType;
use App\Form\SearchType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// préfixe des routes pour les differentes méthodes concernant les sorties
#[Route ('/sortie', name : 'sortie')]
// accès à la page des sorties uniquement lorsque le user est connectée
#[IsGranted( 'ROLE_USER')]

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
     * Méthode pour afficher le déatails d'un sortie avec l'id de la sortie
     */
    #[Route('/detail/{sortie}', name: '_detail')]
    public function detail(
        Sortie  $sortie,
    ): Response
       {
        $datedujour = new DateTime('today');

        return $this->render('sortie/detail.html.twig', compact('sortie','datedujour'));
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
                    if ($sortie->getDateLimiteInscription() < $sortie->getDateHeureDebut()) {
                        if($request->request->has('Enregistrer')) {
                            $etat = $etatRepository->find(1);
                            $sortie->setEtat($etat);
                            $this->addFlash('succes', 'Votre sortie est bien enregistré !');

                        }
                        elseif ($request->request->has('Ajouter')){
                            $etat = $etatRepository->find(2);
                            $sortie->setEtat($etat);
                            $this->addFlash('succes', 'Votre sortie vient d\'être publié');
                        }
                        $sortie->setOrganisateurs($this->getUser());

                        $entityManager->persist($sortie);
                        $entityManager->flush();
                        $this->addFlash('succes','Sortie crée avec succés');
                        return $this->redirectToRoute('sortie_lister');
                    } else {
                        $this->addFlash('echec', 'La date limite d\'inscription doit être inférieur a la date de début.');
                    }
                }

        }catch (Exception $exception){
            $this->addFlash('echec', 'La sortie n\'a  pas été insérée');
//             return $this->redirectToRoute('sortie_ajouter');
        }
        return $this->render('sortie/ajouter.html.twig', compact('sortieForm'));



    }
    #[Route('/lieurecuperer/{ville}', name: '_ajouterLieurecuperer')]
    public function lieurecuperer(
        Ville $ville
    ): Response
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
    /* Méthodes pour supprimer une sortie à partir de l'id de la sortie et du fait que l'organisteur est le seul
    * à pouvoir supprimer la sortie
    */
    #[Route('/detail/{sortie}/annuler', name: '_annulerMaSortie')]
    public function annulerMaSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        Sortie $sortie,

    ): Response
    {
        $etat= $entityManager->getRepository(Etat::class)->find(310);
//
        $annulerForm = $this->createForm(AnnulerMaSortieType::class, $sortie);
        $annulerForm->handleRequest($request);
        {
            if($annulerForm->isSubmitted()){
                try{
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('succes','Votre sortie a bien été supprimée');
                return $this->redirectToRoute('sortie_lister');
                } catch(Exception $exception){
                    $this->redirectToRoute('sortie_detail');
                }
            }

            return $this->render('sortie/annulerMaSortie.html.twig', compact('annulerForm', 'sortie'));
        }
    }
    #[Route('/detail/{sortie}/inscription', name: '_inscription')]
    public function sortieInscription(
        Sortie                  $sortie,
        EntityManagerInterface  $entityManager
    ): Response
    {
        $user = $this->getUser();
        $participants = $sortie->getParticipants();
        $inscritMax = $sortie->getNbInscriptionMax();
        $datedebutsortie = $sortie->getDateHeureDebut();
        $datefininscription = $sortie->getDateLimiteInscription();
        $datedujour = new DateTime('now');

        if($datedebutsortie >= $datedujour || $datefininscription >= $datedujour){
            if(empty($participants)) {
                $sortie->addParticipant($user);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }else {
                if(count($participants) < $inscritMax) {
                    $sortie->addParticipant($user);
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                }else{
                    throw new ('La sortie est complète ! comme une galette ( complète!)');
                }
            }
        }
        return $this->render('sortie/detail.html.twig', compact('sortie', 'datedujour'));

    }

    /*
     * Méthode pour se descinscrir d'une sortie
     */
    #[Route('/detail/{sortie}/déinscription', name: '_sedesinscrire')]
    public function sedesinscrire(
        Sortie $sortie,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $user = $this->getUser();
        $participants = $sortie->getParticipants();


        if(empty($participants)){
            throw new(' Il n\'y a aucun inscrit pour cette sortie');
        }

        foreach ($participants as $participant){
            if($user === $participant){

                $sortie->removeParticipant($user);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }
    return $this->render('sortie/detail.html.twig', compact('sortie'));
    }

}

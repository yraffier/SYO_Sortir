<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class VilleController extends AbstractController
{

    /**
     *
     * Fonction qui ajout un lieu et une ville
     *
     *
     * @param LieuRepository $lieuRepository
     * @param VilleRepository $villeRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     *
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/ville', name: 'ville_ajouter')]
    public function ajouter(
        LieuRepository         $lieuRepository,
        VilleRepository        $villeRepository,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {

        //Récupération des valeurs du lieu et de la ville
        $nomRecuperee = $request->request->get("inputNom");
        $rueRecuperee = $request->request->get("inputRue");
        $villeRecuperee = $request->request->get("inputVille");
        $cpRecupere = $request->request->get("inputCodePostal");
        $latRecupere = $request->request->get("inputLat");
        $longRecupere = $request->request->get("inputLong");


        if (!empty($rueRecuperee)) {

            //creer une nouvelle ville
            $ville = new Ville();
            //initialisation du la ville
            $ville->setNom($villeRecuperee);
            $ville->setCodePostal($cpRecupere);

            //creer un nouveau lieu
            $lieu = new Lieu();
            //initialisation du lieu
            $lieu->setNom($nomRecuperee);
            $lieu->setRue($rueRecuperee);
            $lieu->setLatitude($latRecupere);
            $lieu->setLongitude($longRecupere);
            $villebd = $villeRepository->verificationDeDoublonVille($ville);


            //Verification si le lieu est présent dans la base de donnée
            if ($lieuRepository->verificationDeDoublonLieu($lieu)) {

                //Verification si la ville est en base de donnée
                if ($villebd) {
                    $this->addFlash('echec', 'Le lieu que vous essayez d\'inserer existe déjà');

                } else {
                    $ville = new Ville();
                    $ville->setNom($villeRecuperee);
                    $ville->setCodePostal($cpRecupere);

                    $lieu->setVille($ville);
                    $entityManager->persist($lieu);
                    $entityManager->flush();
                    $this->redirectToRoute('sortie_ajouter');
                }
            } else {

                //Verification si la ville est en base de donnée
                if ($villebd) {
                    $lieu->setVille($villebd);
                } else {
                    $ville = new Ville();
                    $ville->setNom($villeRecuperee);
                    $ville->setCodePostal($cpRecupere);

                    $lieu->setVille($ville);
                }
                $entityManager->persist($lieu);
                $entityManager->flush();
                $this->redirectToRoute('sortie_ajouter');

            }

        }

        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }
}

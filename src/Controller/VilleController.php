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

class VilleController extends AbstractController
{
    #[Route('/ville', name: 'ville_ajouter')]
    public function ajouter(
        LieuRepository $lieuRepository,
        VilleRepository $villeRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $nomRecuperee = $request->request->get("inputNom");
        $rueRecuperee = $request->request->get("inputRue");
        $villeRecuperee = $request->request->get("inputVille");
        $cpRecupere = $request->request->get("inputCodePostal");
        $latRecupere = $request->request->get("inputLat");
        $longRecupere = $request->request->get("inputLong");


        if (!empty($rueRecuperee))
        {
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



//            check si le lieu est présent dans la bd
            if ($lieuRepository->verificationDeDoublonLieu($lieu)) {
                if ($villebd){
                    $this->addFlash('echec', 'Le lieu que vous essayez d\'inserer existe déjà');

                } else {
                    //set id de ville
                    $ville = new Ville();
                    $ville->setNom($villeRecuperee);
                    $ville->setCodePostal($cpRecupere);

                    $lieu->setVille($ville);
                    $entityManager->persist($lieu);
                    $entityManager->flush();
                }
            } else {
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

            }

        }

        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }
}

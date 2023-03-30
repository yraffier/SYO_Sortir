<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ProfilUtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\Curl\Util;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'sortir')]
class UtilisateurController extends AbstractController
{
    #[Route('', name: '_accueil')]
    public function index(): Response
    {
        return $this->render('utilisateur/accueil.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route(
        'sortie/monprofil',
        name: '_profil'
    )]
    public function affichermonprofil(
        Request                 $request,
        EntityManagerInterface  $entityManager
    ): Response
    {
        $utilisateur = $this->getUser();
        if(!$utilisateur){
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }
        $userForm = $this->createForm(ProfilUtilisateurType::class, $utilisateur);
        $userForm->handleRequest($request);


        if($userForm->isSubmitted() && $userForm->isValid()){
            try{
                $entityManager->persist($utilisateur);
                $entityManager->flush();
                return $this->redirectToRoute('sortie_lister');
            }catch(\Exception $exception){
                $this->addFlash('danger','Erreur lors de la modification de votre profil !');
                return $this->redirectToRoute('sortir_profil');
            }
        }
        return $this->render(
            'utilisateur/profil.html.twig',
            compact('utilisateur', 'userForm')
        );
    }

    #[Route(
        'sortie/profil/{utilisateur}',
        name: '_detailProfil'
    )]
//    #[Entity('utilisateur', options: ['id' => 'utilisateur_id'])]
    public function afficherprofil(
        Utilisateur $utilisateur
    ): Response
    {
        if (!$utilisateur) {
            throw $this->createNotFoundException('Ce wish n\'existe pas');
        }

        return $this->render('utilisateur/detailprofil.html.twig', compact("utilisateur"));
    }



}

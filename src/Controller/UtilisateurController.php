<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ProfilUtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
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
        'sortie/monprofil/{utilisateur}',
        name: '_profil'
    )]
    public function afficherprofil(
        Request                 $request,
        EntityManagerInterface  $entityManager,
        Utilisateur             $utilisateur
    ): Response
    {

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



}

<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/*
 * Controller qui permet de l'inscription d'un utilisateur
 */

class RegistrationController extends AbstractController
{
    //private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        // $this->emailVerifier = $emailVerifier;
    }

    #[Route('/inscription', name: 'inscription_sortir')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager
    ): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $campus = $entityManager->getRepository(Campus::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('admin-sortir@sortir.com', 'Administrateur'))
//                    ->to($user->getMail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('login_sortir');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'campus' => $campus,
        ]);
    }

//    #[Route('/verify/email', name: 'app_verify_email')]
//    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
//    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
//        // validate email confirmation link, sets User::isVerified=true and persists
//        try {
//            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
//        } catch (VerifyEmailExceptionInterface $exception) {
//            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
//
//            return $this->redirectToRoute('login_sortie');
//        }
//
//        // @TODO Change the redirect on success and handle or remove the flash message in your templates
//        $this->addFlash('success', 'Your email address has been verified.');
//
//        return $this->redirectToRoute('CompteUtilisateur_sortie');
//    }
}

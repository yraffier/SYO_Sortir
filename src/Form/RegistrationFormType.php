<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextareaType::class, [
                'required' => true,
                'label' => 'Creation de votre Pseudo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Hep Hep Hep ! Il te faut un nom de scéne ! (Je te vois trichez avec la barre d\'espace)'
                    ]),
                    new NotNull([
                        'message' => 'Es-tu nul ? NON ! alors remplit ce champs'
                    ]),
                ]
            ])
            ->add('nom', TextType::class,[
                'required' => true,
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Pas d\'espace déso mais pas déso'
                    ]),
                    new NotNull([
                        'message' => 'Genre... tu n\'as pas de nom, je ne lâcherais pas il m\'en faut un !'
                    ])
                ]
            ])
            ->add('prenom', TextType::class,[
                'required' => true,
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Pas d\'espace déso mais pas déso'
                    ]),
                    new NotNull([
                        'message' => 'Si tu n\'as pas de prénom je ne pourrais pas t\'écrire ;)'
                    ])
                ]
            ])
            ->add('telephone', TextType::class,[
                'required' => true,
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Pas d\'espace déso mais pas déso'
                    ]),
                    new NotNull([
                        'message' => 'Lâche ton num ! Please !'
                    ])
                ]
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' => 'Mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Pas d\'espace déso mais pas déso'
                    ]),
                    new NotNull([
                        'message' => 'Le mail est obligatoire ! Promis pas de spam, ni de revente à des sites partenaires'
                    ])
                ]
            ])
            ->add('campus', EntityType::class,
                        [
                            'required' => true,
                            'class' => Campus::class,
                            'choice_label' => 'nom',
                        ]
            )
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => '',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de Passe',
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotNull([
                        'message' => 'Euh... Juste, tu veux te connecter comment sans mot de passe ! DAH! ',
                    ]),
                    new NotBlank([
                        'message' => 'Pas d\'espace déso mais pas déso',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => '{{ limit }} caractères, c\'est à peu près tout ce que je demande !' ,
                        'max' => 100,
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

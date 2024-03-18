<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled'=> true,
                'label'=>"Mon adresse email"
            ])
            ->add('firstname', TextType::class, [
                'disabled'=> true,
                'label'=>"Mon prénom"
            ])
            ->add('lastname', TextType::class, [
                'disabled'=> true,
                'label'=>"Mon nom"
            ])
            ->add('old_password', PasswordType::class, [
                'label' => "Mon mot de passe actuel",
                'mapped'=> false, // On indique ici que ce champs de formulaire ne sera pas lié à l'entité User, parce que c'est nous qui allons faire le travaille dans 'AccountPasswordController'.
                //Si on ne fait pas ça, Symfony essayera d'aller chercher dans user 'old_password'
                'attr' => [
                    'placeholder' => "Veuillez saisir votre mot de passe actuel"
                ]
            ])
            ->add('new_password', RepeatedType::class, [ // nous configurons ici un champs de type RepeatedType. Ce type permet de créer deux inputs qui devront contenir la même chose.
                'type'=> PasswordType::class, //on determine que les champs répétés seront de type password.
                'mapped'=> false,// On indique ici que ce champs de formulaire ne sera pas lié à l'entité User, parce que c'est nous qui allons faire le travaille dans 'AccountPasswordController'.
                //Si on ne fait pas ça, Symfony essayera d'aller chercher dans user 'new_password'
                'constraints'=> [
                    new NotBlank([
                        'message'=>'Merci de saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit au moins faire {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' =>'Votre mot de passe doit faire au maximum {{ limit }} caractères',
                    ])
                ],
                'invalid_message'=> 'Le mot de passe et sa confirmation doivent être identiques', // permet d'indiquer un message d'erreur
                'label'=> 'Mon nouveau mot de passe',
                'required' => true,
                'first_options' => ['label' => 'Nouveau mot de passe', // first_options nous permet de gérer les attributs et options du premier champs
                    'attr'=>['placeholder'=>'Veuillez saisir votre nouveau mot de passe']],
                'second_options' => ['label' => 'Confirmez le nouveau mot de passe', // second_options nous permet de gérer les attributs et options du deuxième champs
                    'attr'=>['placeholder'=>'Veuillez confirmer le nouveau mot de passe']]
            ])
            ->add('submit', SubmitType::class,[ // ce champs de type submitType crée un bouton pour pouvoir soumettre notre formulaire.
                'label'=>"Mettre à jour"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

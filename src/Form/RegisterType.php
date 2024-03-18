<?php
// registerType est le fichier qui permet de décrire et de configurer le formulaire qui est lié à l'entité User
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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [ // chaque ligne suivi de add apparaitra dans notre vue
                'label'=> 'Votre prénom', // on peut configurer des parametres spécifiques pour chaque champs
                'constraints'=> [ // 'constraints' nous permet de mettre en place des contraintes pour sécuriser notre formulaire
                  new NotBlank([
                      'message'=>'Merci de saisir un prénom',
                  ]),
                  new Length([
                      'min' => 2,
                      'minMessage' => 'Votre prénom doit au moins faire {{ limit }} caractères',
                      'max' => 50,
                      'maxMessage' =>'Votre prénom doit faire au maximum {{ limit }} caractères',
                  ])
                ],
                'attr'=> [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [ // ici, textType permet d'indiquer que l'on a affaire à un champ de type texte. Donc, dans la vue, le l'input sera de type "text".
                'label'=> 'Votre nom', // on configure le label
                'constraints'=> [
                    new NotBlank([
                        'message'=>'Merci de saisir un nom',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit au moins faire {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' =>'Votre nom doit faire au maximum {{ limit }} caractères',
                    ])
                ],
                'attr'=> [ // grâce à 'attr', on peut ajouter des attributs suplémentaires
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class, [ // nous configurons ici un champs de type email. Il aura donc des restrictions dans la vue des restrictions liées au language html
                'label'=> 'Votre email',
                'constraints'=> [
                  new Email([
                      'message' => "L'email {{ value }}, n'est pas valide. Merci de saisir un email valide."
                  ])
                ],
                'attr'=> [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ])
            ->add('password', RepeatedType::class, [ // nous configurons ici un champs de type RepeatedType. Ce type permet de créer deux inputs qui devront contenir la même chose.
                'type'=> PasswordType::class, //on determine que les champs répétés seront de type password.
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
                'label'=> 'Mot de passe',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe', // first_options nous permet de gérer les attributs et options du premier champs
                                    'attr'=>['placeholder'=>'Veuillez saisir un mot de passe']],
                'second_options' => ['label' => 'Confirmez le mot de passe', // second_options nous permet de gérer les attributs et options du deuxième champs
                                    'attr'=>['placeholder'=>'Veuillez confirmer le mot de passe']]
            ])
            ->add('submit', SubmitType::class,[ // ce champs de type submitType crée un bouton pour pouvoir soumettre notre formulaire.
                'label'=>"S'inscrire"
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

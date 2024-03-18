<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager )
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification= null;

        $user =$this->getUser(); // Récupération de l'utilisateur courant.
        $form =$this->createForm(ChangePasswordType::class, $user); // Création du formulaire en lien avec notre utilisateur courant.

        $form->handleRequest($request); // Avec handleRequest et $request, on écoute la requête de notre formulaire.

        if($form->isSubmitted() && $form->isValid()){// Si mon formulaire est valide et soumis, envoye les données du formulaire dans l'objet $user.
            $old_password = $form->get('old_password')->getData(); // On récupère le mot de passe saisi dans le champ de l'ancien mot de passe.

            if($passwordHasher->isPasswordValid($user, $old_password)){ // Avec isPasswordValid, on compare la valeur saisie dans le champ et la valeur stockée en base de données.
                $new_password = $form->get('new_password')->getData(); // Si le if est à 'true', on récupère alors la valeur indiquée dans le champ 'RepeatedType' 'Mon mot de passe actuel'.
                $password = $passwordHasher->hashPassword($user, $new_password); // La méthode 'hashPassword' hash la valeur contenue dans '$new_password'.

                $user->setPassword($password); // Dans l'objet $user, met à jour la valeur du mot de passe avec le setter 'setPasssword' et la variable '$password', qui contient le nouveau mot de passe hashé.

                $this->entityManager->flush(); // Maintenant, on pousse le tout en base de données.
                $notification = "Votre mot de passe a bien été mis à jour";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig', [
            'form'=>$form->createView(), // CreateView est une méthode qui transforme le formulaire en une instance de vue du formulaire.
            'notification'=> $notification // Injecte dans la vue la valeur de $notification.
        ]);
    }
}

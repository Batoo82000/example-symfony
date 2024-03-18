<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager )
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'inscription')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User(); // on instancie l'objet user
        $form = $this->createForm(RegisterType::class, $user); // Crée et retourne un formulaire en fonction de l'objet(entité) User qui a été stocké dans $user et de RegisterType

        $form->handleRequest($request); // avec handleRequest et $request, on écoute la requête de notre formulaire

        if ($form->isSubmitted() && $form->isValid()) { // si mon formulaire est valide et soumis, envoye les données du formulaire dans l'objet $user

            $user = $form->getData(); // Injection des données du formulaire dans l'objet $user
            $password = $passwordHasher->hashPassword($user, $user->getPassword()); // Ici on crée une varialble password, qui avec la dépendence 'UserPasswordHasherInterface', permet de hasher une valeur avec la méthode 'hashPassword', en sélectionnant $user, puis en récupérant la valeur du mot de passe avec getPassword. On obient alors une valeur hashée qui est maintenant stockée dans '$password'.
            $user->setPassword($password); // Maintenant, on injecte la valeur hashée du mot de passe saisie dans le formualire dans l'objet user avec 'setPassword'
            $this->entityManager->persist($user); // Indique à doctirne que l'on veut (éventuellement), sauver l'utilisateur (mais on a pas encore de requête)
            $this->entityManager->flush(); // Execute la requête (ici, un INSERT), donc, met en base de données les infos.

            return new Response('Saved new user with id ' . $user->getFirstname());
        }

        return $this->render('register/index.html.twig', [
            'form'=>$form->createView() //createView est une méthode qui transforme le formulaire en une instance de vue du formulaire
        ]);
    }
}

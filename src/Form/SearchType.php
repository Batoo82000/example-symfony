<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType { //Formulaire de notre recherche custom

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [ // 1er champs qui sera une recherche textuelle
                'label' => false,
                'required' => false,
                'attr'=> [
                    'placeholder' => 'Votre recherche ...'
                ]
            ])
            ->add('categories', EntityType::class, [ //Le 2eme champs est de type EntityType, ce qui nous permet de lier une classe à l'input.
                'label' => false,
                'required' => false,
                'class'=> Category::class, // Grâce à cette ligne, on lie l'entité désirée à notre input
                'multiple'=> true, // es options multiple et expanded sont définies à true, ce qui signifie que l'utilisateur pourra sélectionner plusieurs catégories et que les options seront affichées sous forme de cases à cocher.
                'expanded'=>true
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'filtrer',
                'attr'=> [
                    'class'=> 'btn-block btn-info'
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class, // La classe de données associée au formulaire est Search::class.
            'method'=> 'GET', // La méthode d'envoi du formulaire est définie sur "GET".
            'crsf_protection' => false, // a protection CSRF est désactivée (crsf_protection est défini sur false). Cela signifie que le formulaire ne générera pas automatiquement de jeton CSRF pour protéger contre les attaques CSRF.
        ]);
    }
    public function getBlockPrefix() //  Retourne une chaîne vide, ce qui signifie que le formulaire n'aura pas de préfixe. Cela signifie que les noms des champs dans le formulaire ne seront pas préfixés par le nom du formulaire lorsqu'ils sont rendus dans le HTML
    {
        return '';
    }
}
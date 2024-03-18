<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager) // Dans le constructeur de la classe, une dépendance de type EntityManagerInterface est injectée. Cela signifie que chaque instance de ProductController doit recevoir un objet EntityManagerInterface lors de sa création.
    {
        $this->entitymanager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {


        $search = new Search(); // Dans cette méthode, un objet Search est instancié. Cet objet est utilisé pour stocker les données de recherche provenant du formulaire.
        $form = $this->createForm(SearchType::class, $search); // Un formulaire est créé en utilisant la méthode $this->createForm(). On utilise le type de formulaire SearchType défini précédemment, et on lui passe l'objet Search comme donnée.

        $form->handleRequest($request); // '$form->handleRequest($request)' est appelée pour que le formulaire traite la requête HTTP actuelle. Cela permet de mettre à jour l'objet Search avec les données soumises par le formulaire, si elles existent.

        if($form->isSubmitted() && $form->isValid()) { // On vérifie si le formulaire a été soumis et est valide en utilisant $form->isSubmitted() et $form->isValid(). Si c'est le cas, cela signifie que l'utilisateur a soumis le formulaire et que les données sont valides.
            $products = $this->entitymanager->getRepository(Product::class)->findWithSearch($search); // Une recherche de produits est effectuée en utilisant la méthode findWithSearch() de l'objet ProductRepository, en passant l'objet Search comme paramètre.
        } else { // Si le formulaire n'a pas été soumis ou n'est pas valide, cela signifie que l'utilisateur n'a pas encore soumis de formulaire ou que les données soumises ne sont pas valides. Dans ce cas, tous les produits sont récupérés en utilisant la méthode findAll() de l'objet ProductRepository.
            $products = $this->entitymanager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [ // La méthode render() est utilisée pour afficher le template 'product/index.html.twig'. Les produits récupérés sont transmis au template sous le nom 'products', et le formulaire créé est transmis sous le nom 'form'.
            'products' => $products,
            'form'=> $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response // La méthode show est définie pour gérer l'affichage d'un produit individuel.
    {

        $product = $this->entitymanager->getRepository(Product::class)->findOneBySlug($slug); // Un produit est récupéré en utilisant la méthode findOneBySlug() de l'objet ProductRepository, en passant le slug du produit comme paramètre.

        if(!$product) { // Si aucun produit n'est trouvé avec le slug donné, l'utilisateur est redirigé vers la page d'index des produits.
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [ // Si un produit est trouvé, il est transmis au template 'product/show.html.twig' pour être affiché.
            'product' => $product,
        ]);
    }
}

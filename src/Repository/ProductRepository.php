<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository // La classe ProductRepository étend ServiceEntityRepository, qui est fournie par Doctrine pour faciliter l'accès aux entités dans la base de données.
{
    public function __construct(ManagerRegistry $registry) // Dans le constructeur de la classe, la méthode __construct() est appelée avec deux arguments : le ManagerRegistry et la classe de l'entité gérée, dans ce cas Product. Cela initialise le repository en utilisant le registre de gestionnaires d'entités (ManagerRegistry) et la classe de l'entité Product.
    {
        parent::__construct($registry, Product::class);
    }
    /**
     * Requête qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur.
     * @return Product[]
     */
    public function findWithSearch(Search $search): array // La méthode nommée findWithSearch est définie. Elle prend un objet de type Search comme argument et renvoie un tableau de produits correspondant aux critères de recherche.
    {
        // Une requête de type QueryBuilder est créée en utilisant la méthode createQueryBuilder(). Cette méthode crée une requête pour sélectionner des objets de l'entité Product (représentés par la lettre 'p' dans la requête) ainsi que des objets de l'entité Category (représentés par la lettre 'c' dans la requête).
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c'); // Ici, une jointure est faite entre la table Product et la table Category

        // La méthode vérifie si des catégories ont été sélectionnées dans l'objet Search. Si c'est le cas, elle ajoute une clause WHERE à la requête pour filtrer les produits en fonction des catégories sélectionnées.
        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id in (:categories)')
                ->setParameter('categories', $search->categories);
        }
        // La méthode vérifie si une chaîne de recherche a été saisie dans l'objet Search. Si c'est le cas, elle ajoute une clause WHERE pour rechercher des produits dont le nom contient cette chaîne.
        if(!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }
        // La méthode exécute la requête en appelant getQuery() pour obtenir l'objet Query correspondant à la requête construite, puis getResult() pour obtenir les résultats de la requête sous forme de tableau de produits.
        return $query->getQuery()->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseActivitySubscriber implements EventSubscriber
{
    /** @var KernelInterface $appKernel */
    private $appKernel;
    private $rootDir;

    public function __construct(KernelInterface $appKernel)
    {
        // Le constructeur est appelé lorsqu'une instance de cette classe est créée.
        // Il prend une instance de KernelInterface en tant que dépendance, qui est utilisée pour obtenir le chemin du projet.
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }

    public function getSubscribedEvents(): array
    {
        // Cette méthode indique à Doctrine quel(s) événement(s) doit écouter cet abonné.
        return [
            Events::postRemove, // Écoute l'événement postRemove qui est déclenché après la suppression d'une entité dans la base de données.
        ];
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        // Cette méthode est appelée automatiquement lorsqu'un événement postRemove est déclenché.
        // Elle récupère les arguments de l'événement et appelle la méthode logActivity pour enregistrer l'activité.
        $this->logActivity('remove', $args->getObject());
    }

    public function logActivity(string $action, mixed $entity): void
    {
        // Cette méthode enregistre une activité (logActivity) en fonction de l'action effectuée (remove) sur une entité spécifique.

        // Si l'entité est une instance de Product et l'action est "remove"
        if(($entity instanceof Product) && $action === "remove"){
            // Supprime l'image associée à Product.
            $filename = $entity->getIllustration();
            $filelink = $this->rootDir. "/public/assets/images/sliders/".$filename;
            dd($filelink);
            $this->deleteImage($filelink);
        }
    }

    public function deleteImage(string $filelink): void
    {
        // Cette méthode tente de supprimer un fichier (image) du système de fichiers.
        try {
            $result = unlink($filelink); // unlink est une fonction PHP qui supprime un fichier.
        } catch (\Throwable $th) {
            // En cas d'erreur (par exemple, si le fichier n'existe pas), elle ne fait rien.
        }
    }
}
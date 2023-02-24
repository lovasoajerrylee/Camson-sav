<?php
namespace App\Service;
use Symfony\Component\DependencyInjection\ContainerInterface; 
class  UploadsPhotos {

    private $container; 
    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }
    public function uploadImages($image){
                // On génère un nouveau nom de fichier
                $now = new \DateTime('now');
                $date= $now->format('Ymd');
                if ($image != "") {
                    $fichier = $date.md5(uniqid()).'.'.$image->guessExtension();
                        // On copie le fichier dans le dossier uploads
                    $image->move(
                    $this->container->getParameter('images_directory'),
                    $fichier
                 );
                return $fichier;  
             }
            
        }
}
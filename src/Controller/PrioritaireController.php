<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Prioritaire;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PrioritaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PrioritaireController extends AbstractController
{

    private $em;
    private $PrioritaireRepository;

    public function __construct(PrioritaireRepository $PrioritaireRepository, EntityManagerInterface $em)
    {
        $this->PrioritaireRepository = $PrioritaireRepository;
        $this->em = $em;
        
    }

    #[Route('/prioritaire/allPrioritaire', name: 'app_prioritaire_all')]
    public function getAllPrioritaire()
    {
        $prioritaire = $this->PrioritaireRepository->findAll();
        $response = $this->json(
            $prioritaire,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['prioritaire:read']]
        );
        return $response;
    }

    #[Route('/prioritaire/addPrioritaire', name: 'app_addPrioritaire')]
    public function addPrioritaire(Request $request)
    {
        $prioritaire = new Prioritaire();
        
        $niveau = $request->request->get("niveau");

        $prioritaire->setNiveau($niveau);

        $this->em->persist($prioritaire);
        $this->em->flush();
            
        return new JsonResponse([
            "message"=> 'Ok',
            "code"=> 200,
        ]); 
    }
}

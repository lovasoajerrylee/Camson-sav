<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Prioritaire;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PrioritaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PrioritaireApiController extends AbstractController
{

    private $em;
    private $PrioritaireRepository;

    public function __construct(PrioritaireRepository $PrioritaireRepository, EntityManagerInterface $em)
    {
        $this->PrioritaireRepository = $PrioritaireRepository;
        $this->em = $em;
        
    }

    //============================= get all prioritaire ===========================
    #[Route('/api/prioritaire/allPrioritaire', name: 'api_prioritaire_all', methods:['GET'])]
    public function getAllPrioritaire()
    {
        $prioritaire = $this->PrioritaireRepository->findAll();
        return $this->json(
            [
                "success"=>true,
                "data"=>$prioritaire
            ],
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['prioritaire:read']]
        );
    }
//========================= fin list prioritaire =============================
}

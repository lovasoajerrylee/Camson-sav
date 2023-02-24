<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Magasin;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiMagasinController extends AbstractController
{

    private $em;

    public function __construct(MagasinRepository $MagasinRepository, EntityManagerInterface $em)
    {
        $this->MagasinRepository = $MagasinRepository;
        $this->em = $em;
        
    }
//==================== All Magasin =======================================
    #[Route('/api/magasin/allMagasin', name: 'api_all_magasin',methods: ['GET'])]
    public function getAllMagasin()
    {
        $magasin = $this->MagasinRepository->findAll();
        $response = $this->json([
            "success" => true,
            "data"=>$magasin
        ],
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['magasin:read']]
        );
        return $response;
    }
    //===================================== fin All Magasin ==========================
}
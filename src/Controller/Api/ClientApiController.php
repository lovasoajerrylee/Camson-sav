<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class ClientApiController extends AbstractController
{
    private $em;

    public function __construct(ClientRepository $ClientRepository, EntityManagerInterface $em)
    {
        $this->ClientRepository = $ClientRepository;
        $this->em = $em;
        
    }
    //==================== All Client =============================
    #[Route('/api/client/all_Client', name: 'api_client_all_client', methods: ['GET'])]
    public function getAllMagasin()
    {
        $client = $this->ClientRepository->findAll();
        $response = $this->json([
            "success"=>true, 
            "data"=>$client
        ],
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['client:read']]
        );
        return $response;
    }
    //============================ fin All Client ========================
}
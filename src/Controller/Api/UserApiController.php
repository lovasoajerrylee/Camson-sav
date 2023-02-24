<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class UserApiController extends AbstractController
{
    private $em;
    private $UserRepository;

    public function __construct(
        UserRepository $UserRepository,
        EntityManagerInterface $em
    ) {
        $this->UserRepository = $UserRepository;
        $this->em = $em;
    }
//==================== Touts les users =====================================
    #[Route('/api/utilisateur/allUser', name: 'api_all_user', methods:['GET'])]
    public function getAllUser()
    {

        $User = $this->UserRepository->findAll();
        $response = $this->json([
            "success"=>true, 
            "data"=>$User
        ],
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'user:read'
                ],
            ]
        );
        return $response;
    }
//==================== fin Touts les users =====================================

//==================== info les users =====================================
#[Route('/api/utilisateur/getUserInfo/{email}', name: 'api_user_info', methods: ['GET'])]
public function getInfoUser(Request $request) :JsonResponse
{
    $email = $request->get('email');
    $user = $this->UserRepository->findOneBy(["email"=>$email]);
    return $this->json([
        "success"=>true, 
        "data"=>$user],
        200,['Content-Type' => 'appication/json'],
        ['groups' => ['user:read']]);
}
//=========================fin info users =============================

    //=================== get usr by roles technicien ================
    #[Route('/api/utilisateur/userTech', name: 'api_tech_user', methods:['GET'])]
    public function listTechByRole(): JsonResponse
    {
    $tech = $this->UserRepository->findAllUserByRole();
    return $this->json([
        "success"=>true, 
        "data"=>$tech]
        , 200,['Content-Type' => 'appication/json'],
        ['groups' => ['user:read']]);
        }
//=================== fin get usr by roles technicien ================
}


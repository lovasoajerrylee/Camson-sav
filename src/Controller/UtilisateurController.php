<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UtilisateurController extends AbstractController
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


    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('utilisateur/index.html.twig');
    }

    #[Route('/utilisateur/allUser', name: 'app_User_all_User')]
    public function getAllUser()
    {
        $User = $this->UserRepository->findAll();
        $response = $this->json(
            $User,
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

    #[Route('/utilisateur/allUserTech', name: 'app_User_all_User_tech')]
    public function getAllUserTech()
    {
        $User = $this->UserRepository->findAllUserByRole();
        $response = $this->json(
            $User,
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
    #[Route('/utilisateur/changeRoleAdmin', name: 'app_changeRoleAdmin')]
    public function changeRoleAdmin(Request $request)
    {
        $id = $request->get('id');

        $user = $this->UserRepository->find($id);
        

        $user->setRoles(['ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/utilisateur/changeRoleTechnicien', name: 'app_changeRoleTechnicien')]
    public function changeRoleTechnicien(Request $request)
    {
        $id = $request->get('id');

        $user = $this->UserRepository->find($id);

        $user->setRoles(['ROLE_TECH']);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
    
    #[Route('/utilisateur/detail', name: 'getIdUser')]
    public function getIdUser(Request $request)
    {
      $id = $request->get('id');
      $user = $this->UserRepository->find($id);
      return $this->json(
        $user,
        200,
        ['Content-Type' => 'appication/json'],
        [
            'groups' => [
                'user:read',
            ],
        ]
        );
    }
    #[Route('/getId', name: 'app_user_findById')]
    public function getIdDetaile(Request $request)
    {
        $id = $request->get('id');
        $user = $this->UserRepository->find($id);
        $response = $this->json(
            $user,
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
    #[Route('/user/modif', name: 'app_delete')]

    public function delete(Request $request)
    {

        $id = $request->get('id');

        $user = $this->UserRepository->find($id);

       
        $id = $request->request->get('id');
    
       
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200
        ]);
    }

    #[Route('/user/delete', name: 'app_user_delete')]
    public function deleteMagasin(Request $request)
    {
        $id = $request->get('id');

        $user = $this->UserRepository->find($id);
        $this->em->remove($user);
        $this->em->flush();
        
        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
}


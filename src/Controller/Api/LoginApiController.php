<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ManagerRegistry;


class LoginApiController extends ApiController
{

    private $em;
    private $repository;

    public function __construct( EntityManagerInterface $em, UserRepository $repository) 
    {
        $this->repository = $repository;
        $this->em = $em;
    }
   
     /**
     * registration fonction require username,password and valid email
     * 
     * @Route("/api/register", name="register", methods={"POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @return JsonResponse
     * 
     */
    public function register(Request $request, UserPasswordHasherInterface $encoder) :JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($username) || empty($password) || empty($email)) {
            $array = [
                "success" => false,
                "code" => 422,
                "message" => "Invalid username or password or email address"
            ];
            return new JsonResponse($array);
        }
        $user = new User($username);
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);
        $this->em->persist($user);
        $this->em-> flush();

        $array = [
            "success" => true,
            "code" => 200,
            "message" => "Users created successfully"
        ];
       return new JsonResponse($array);
    }

     /**
     * @Route("/api/login_check", name="login_check", methods={"POST"})
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function login(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse {

        if(null !== $user) {
            $array = [
                'token' => $JWTManager->create($user),
                'code' => 200,
                'message' => "Login successful"
            ];
            return new JsonResponse($array);
        }
       
    }

     /**
     * @Route("/api/forgotPasswordAction", name="forgotPasswordAction", methods={"POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordEncoder
     * @return JsonResponse
     */
    public function forgotPasswordAction(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer): JsonResponse {
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');
        $user = $this->repository->findOneBy(['email' => $email]);
        if (!$user) {
            $array=[
                "success" => false,
                "code" => 404,
                "message" => "email not found in database",
            ];
            return new JsonResponse($array, Response::HTTP_NOT_FOUND);
        } else {
            $token = $tokenGenerator->generateToken();
            $token6Begin = substr($token, 0, 7);

            $em = $doctrine->getManager();
            $user->setForgotPasswordToke($token6Begin);
            $em->flush();

            $email = (new Email())
            ->from('alainjohnyr@gmail.com')
            ->to($email)
            ->subject('Mot de passe oublier')
            ->text('Mot de passe oublier')
            ->html('
            <h3>Veuillez-trouvez ci- après le code de reinitialisation de votre mot de passe</h3> 
            : <h3 style="color:blue"; font-size: 12px; font-weight: bold;>'.$token6Begin.'</h3>');
            $mailer->send($email);
            $array = [
                "success" => true,
                "code" =>200,
                "access_token" => $token6Begin
            ];
            return new JsonResponse($array, JsonResponse::HTTP_OK);
        }
    }

    /**
     * registration fonction require username,password and valid email
     * 
     * @Route("/api/new_password", name="new_password", methods={"POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @return JsonResponse
     */
    public function new_password(ManagerRegistry $doctrine,Request $request, UserPasswordHasherInterface $encoder): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $request = $this->transformJsonBody($request);
        $token = $request->get('token');
        $new_password = $request->get('new_password'); 

        if (empty($token) || empty($new_password)) {
            return $this->respondValidationError("Invalid Username or Password or Email");
        }

        $user = $this->repository->findOneBy(['forgotPasswordToke' => $token]);
        if (!$user) {
            return $this->respondValidationError("Token non trouvee");
        }
        $user->setForgotPasswordToke(null);
        $user->setPassword($encoder->hashPassword($user, $new_password));
        $entityManager->flush();
        $array = [
            "success" => true,
            "code" => 200,
            "message" => "Mots de passe bien reinitialiser"
        ];
        return new JsonResponse($array);

    }

    /**
     * @Route("/api/updateUser/{id}", name="updateUser", methods={"PUT", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $usersRepository
     * @return void
     */
    
    // public function updateUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder, UserRepository $usersRepository,$id) {

    //     $user = $userRepository->find($id);

    //     try {
    //         if(!$user){
    //             $data = [
    //                 'success' => false,
    //                 'status' => 404,
    //                 'message' => 'Utilisateur n\existe pas '
    //             ];
    //             return new JsonResponse($data);
    //         }

    //         $request = $this->transformJsonBody($request);

    //         if (!$request || !$request->get('username') || !$request->get('password') || !$request->get('email') ){
    //             throw new \Exception();
    //         }
    //         $user->setEmail($request->get('email'));
    //         $user->setPassword($encoder->hashPassword($user, $request->get('password')));
    //         $user->setUsername($request->get('username'));
    //         $entityManager->flush();

    //         $response = [
    //             "success" => true,
    //             "code" => 200,
    //             "message" => "User updated successfully"
    //         ];

    //         return new JsonResponse($response, Response::HTTP_OK);

    //     } catch (\Exceptions\UserNotFoundException $th) {
    //         $response = [
    //             "success" => false,
    //             "code" => 422,
    //             "message" => "Data not valid"
    //         ];
    //         return new JsonResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    /**
     * @Route("/api/deleteUser/{id}", name="deleteUser", methods={"DELETE"})
     *
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $repository
     * @param [type] $id
     * @return void
     */
    public function deleteUser(EntityManagerInterface $entityManager, UserRepository $repository, $id){

        if (!$this->isGranted('ROLE_ADMIN') || !$this->isGranted('ROLE_SUPER_ADMIN')) {
            return new JsonResponse([
                "success"=> false,
               "message"=> "Seul l'admin ou le super admin peut faire cet operation",
                "code"=> 401
            ], Response::HTTP_UNAUTHORIZED); 
        }
        $user = $repository->find($id);
        if(!$user){
            $response = [
                "success" => false,
                "code" => 404,
                "message" => "User not found"
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        $response = [
            "success" => true,
            "code" => 200,
            "message" => "User deleted successfully"
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    }

}
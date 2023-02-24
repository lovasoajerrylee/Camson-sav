<?php

namespace App\Controller\Api;


use App\Entity\Sav;
use App\Entity\Message;
use App\Repository\SavRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageApiController extends ApiController
{

    private $em;
    private $MessageRepository;
    private $SavRepository;

    public function __construct(MessageRepository $MessageRepository, SavRepository $SavRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->SavRepository = $SavRepository;
        $this->MessageRepository = $MessageRepository;
        $this->UserRepository = $userRepository;
        $this->em = $em;
    }

    //=============================== Ajouter message client =================================
    #[Route('/api/message/addMessageUser', name: 'api_add_message_user', methods: ['POST'])]
    public function addMessageClient(Request $request)
    {
        $request = $this->transformJsonBody($request);
        $message = new Message();
        $time = new \DateTime();

        $messageInput = $request->request->get("message");
        $idSavInput = $request->request->get("sav");

        $findSavId = $this->SavRepository->find($idSavInput);

        $message->setMessage($messageInput);
        $message->setSav($findSavId);
        $message->setReadEtat(0);
        $message->setType(0);
        $message->setDate(\DateTime::createFromFormat('Y-m-d H:i', $time->format('Y-m-d H:i')));

        $this->em->persist($message);
        $this->em->flush();

        return new JsonResponse([
            "message" => 'Ok',
            "code" => 200,
        ]);
    }
    //==================== fin ajouter client ===========================

    //============================== Msg par id SAV =======================
    #[Route('/api/message/getMessageByIdSav/{idSav}', name: 'api_get_message_by_id_sav', methods: ['GET'])]
    public function getMessageByIdSav(Request $request, int $idSav)
    {
        $request = $this->transformJsonBody($request);
        $messageByIdSav = $this->MessageRepository->findBy(['sav' => $idSav]);
        foreach ($messageByIdSav as $value) {
            $senderId = $value->getSav()->getAdminCreer();
            $user = $this->UserRepository->findOneBy(['id' => $senderId]);
            $data[] = [
                'id' => $value->getId(),
                'sender' => $user->getUsername(),
                'message' => $value->getMessage(),
                'type' => $value->isType(),
                'date' => $value->getDate(),
                'readEtat' => $value->isReadEtat()
            ];
        }
        $response = $this->json(
            [
                "success" => true,
                "data" => $data
            ],
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['message:read']]
        );
        return $response;
    }
    //============================= fin Msg par id SAV =================================

    //================================== change etat Read =====================
    #[Route('/api/message/changeEtatToReadAdmin/{idSav}', name: 'api_read_etat', methods: ['GET'])]
    public function changeEtatToReadAdmin($idSav)
    {
        $messageEtatRead = $this->MessageRepository->findBy(['sav' => $idSav, 'readEtat' => '0', 'type' => '1']);
        foreach ($messageEtatRead as $value) {
            $value->setReadEtat(1);
            $this->em->persist($value);
            $this->em->flush();
        }
        $response = $this->json([
            "success" => true,
            "code" => 200
        ]);
        return $response;
    }
    //==================================== fin chnge etat read =====================

//================================== delete message ================================
#[Route('/api/message/deleteMsg/{idMsg}', name: 'api_delete_msg', methods: ['DELETE'])]
public function deleteMsg($idMsg)
{   
    $message = $this->MessageRepository->findOneBy(['id'=>$idMsg,'type' => '1']);
    if ($message)
    {
        $this->em->remove($message);
        $this->em->flush();
        $response = $this->json([
            "success" => true,
            "code" => 200,
            "message" => "Message supprimé"
        ]);
        return $response;
    }else{
        return  $this->json([
            "success" => false,
            "message" => "Message non suprimé"
        ]);
    }
}
//=====================================fin delete message =============================
}

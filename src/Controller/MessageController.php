<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\SavRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{

    private $em;
    private $MessageRepository;
    private $UserRepository;
    private $SavRepository;

    public function __construct(UserRepository $UserRepository, MessageRepository $MessageRepository, SavRepository $SavRepository, EntityManagerInterface $em)
    {
        $this->SavRepository = $SavRepository;
        $this->MessageRepository = $MessageRepository;
        $this->UserRepository = $UserRepository;
        $this->em = $em;
    }

    #[Route('/message/addMessageUser', name: 'app_add_message_user')]
    public function addMessageClient(Request $request)
    {
        $message = new Message();

        $time = new \DateTime();

        $messageInput = $request->request->get("message");
        $idSavInput = $request->request->get("sav");

        $findSavId = $this->SavRepository->find($idSavInput);

        $message->setMessage($messageInput);
        $message->setSav($findSavId);
        $message->setType(1);
        $message->setReadEtat(0);
        $message->setDate(\DateTime::createFromFormat('Y-m-d H:i', $time->format('Y-m-d H:i')));

        $this->em->persist($message);
        $this->em->flush();

        return new JsonResponse([
            "message" => 'Ok',
            "code" => 200,
        ]);
    }

    #[Route('/message/findMessageTechNoRead', name: 'findMessageTechNoRead')]
    public function findMessageTechNoRead()
    {
        $findMessageTechNoRead = $this->MessageRepository->findBy(['readEtat' => '0', 'type' => '0']);

        $response = $this->json(
            $findMessageTechNoRead,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['message:read', 'sav:read', 'client:read']]
        );
        return $response;
    }

    #[Route('/message/setMessageToRead', name: 'setMessageToRead')]
    public function setMessageToRead(Request $request)
    {
        $idMessage = $request->get("idMessage");

        $setMessageToRead = $this->MessageRepository->find($idMessage);
        $setMessageToRead->setReadEtat(1);

        $this->em->persist($setMessageToRead);
        $this->em->flush();

        $response = $this->json(
            $setMessageToRead,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['message:read', 'sav:read', 'client:read']]
        );
        return $response;
    }

    #[Route('/message/getMessageByIdSav', name: 'app_get_message_by_id_sav')]
    public function getMessageByIdSav(Request $request)
    {
        $idSav = $request->get("idSav");
        $messageByIdSav = $this->MessageRepository->findBy(['sav' => $idSav]);
        $username = "";
        foreach ($messageByIdSav as $value) {
            $username = $this->UserRepository->findOneBy(['id' => $value->getSav()->getAdminCreer()])->getUsername();
        }
        $response = $this->json(
            [$messageByIdSav, $username],
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['message:read', 'sav:read', 'client:read']]
        );
        return $response;
    }
}
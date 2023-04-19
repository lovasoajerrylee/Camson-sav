<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/client')]
class ClientController extends AbstractController
{
    private $em;
    private $ClientRepository;

    public function __construct(ClientRepository $ClientRepository, EntityManagerInterface $em)

    {
        $this->em = $em;
        $this->ClientRepository = $ClientRepository;
    }

    #[Route('/client/getAll', name: 'app_client_all_client')]
    public function getAllClient()
    {
        $client = $this->ClientRepository->findAll();
        $response = $this->json(
            $client,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['client:read']]
        );
        return $response;
    }

    #[Route('/', name: 'app_client_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('client/index.html.twig');
    }

    #[Route('/getId', name: 'app_client_findById')]
    public function getIdDetaile(Request $request)
    {
        $id = $request->get('id');
        $sav = $this->ClientRepository->find($id);
        $response = $this->json(
            $sav,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'client:read',
                ],
            ]
        );
        return $response;
    }
    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function addClient(Request $request)
    {
        $client = new Client();

        $nomClient = $request->request->get('nomClient');
        $prenomClient = $request->get('prenomClient');
        $telFixe = $request->request->get('fixe');
        $refClient = $request->request->get('reference');
        $etat = $request->request->get('etat');
        $datePaiment = $request->request->get('datePaiement');
        $nomGerant = $request->request->get('gerant');
        $portable1 = $request->request->get('portable1');
        $portable2 = $request->request->get('portable2');
        $mail = $request->request->get('email');

        $abonnement = "";


        $photocopie = $request->request->get('photocopie');
        $maintenance = $request->request->get('maintenance');

        if (!$maintenance) {
            $abonnement =  $photocopie;
        }

        if (!$photocopie) {
            $abonnement =  $maintenance;
        }


        if ($maintenance && $photocopie) {
            $abonnement = $photocopie . ", " . $maintenance;
        }

        $client->setNomClient($nomClient);
        $client->setPrenomClient($prenomClient);
        $client->setTelPortable1($portable1);
        $client->setTelPortable2($portable2);
        $client->setRefClient($refClient);
        $client->setNomGerant($nomGerant);
        $client->setEtat($etat);
        $client->setTelFixe($telFixe);
        $client->setEmail($mail);
        $client->setAbonnement($abonnement);
        $client->setDatePaiment(
            \DateTime::createFromFormat('Y-m-d', $datePaiment)
        );
        $this->em->persist($client);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/client/modif', name: 'app_edit')]

    public function edit(Request $request)
    {
        $idInputClient = $request->get('id');

        $client = $this->ClientRepository->find($idInputClient);

        $nomClient = $request->request->get('nomClient');
        $prenomClient = $request->get('prenomClient');
        $telFixe = $request->request->get('fixe');
        $refClient = $request->request->get('reference');
        $etat = $request->request->get('etat');
        $datePaiment = $request->request->get('datePaiement');
        $nomGerant = $request->request->get('gerant');
        $portable1 = $request->request->get('portable1');
        $portable2 = $request->request->get('portable2');
        $mail = $request->request->get('email');

        $abonnement = "";


        $photocopie = $request->request->get('photocopie');
        $maintenance = $request->request->get('maintenance');

        if (!$maintenance) {
            $abonnement =  $photocopie;
        }

        if (!$photocopie) {
            $abonnement =  $maintenance;
        }


        if ($maintenance && $photocopie) {
            $abonnement = $photocopie . ", " . $maintenance;
        }

        $client->setNomClient($nomClient);
        $client->setPrenomClient($prenomClient);
        $client->setTelPortable1($portable1);
        $client->setTelPortable2($portable2);
        $client->setRefClient($refClient);
        $client->setNomGerant($nomGerant);
        $client->setEtat($etat);
        $client->setTelFixe($telFixe);
        $client->setEmail($mail);
        $client->setAbonnement($abonnement);
        $client->setDatePaiment(
            \DateTime::createFromFormat('Y-m-d', $datePaiment)
        );

        $this->em->persist($client);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200
        ]);
    }
    #[Route('/client/modif', name: 'app_delete')]

    public function delete(Request $request)
    {

        $idInputClient = $request->get('id');


        $client = $this->ClientRepository->find($idInputClient);



        $id = $request->request->get('id');


        $this->em->persist($client);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200
        ]);
    }

    #[Route('/client/search', name: 'app_client_search')]
    public function searchClient(Request $request)
    {
        $param = $request->get('param');
        $client = $this->ClientRepository->searchClient($param);

        $response = $this->json(
            $client,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['client:read']]
        );
        return $response;
    }

    #[Route('/client/delete', name: 'app_client_delete')]
    public function deleteMagasin(Request $request)
    {
        $idMagasinInput = $request->get('id');
        $magasin = $this->ClientRepository->find($idMagasinInput);
        $this->em->remove($magasin);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
}

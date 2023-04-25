<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Produit;
use App\Repository\AchatRepository;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AchatController extends AbstractController
{

    private $em;
    private $AchatRepository;
    private $ClientRepository;
    private $ProduitRepository;

    public function __construct(AchatRepository $AchatRepository, ClientRepository $ClientRepository, ProduitRepository $ProduitRepository, EntityManagerInterface $em)

    {
        $this->em = $em;
        $this->AchatRepository = $AchatRepository;
        $this->ClientRepository = $ClientRepository;
        $this->ProduitRepository = $ProduitRepository;
    }


    #[Route('/achat', name: 'app_achat')]
    public function index(): Response
    {
        return $this->render('achat/index.html.twig', [
            'controller_name' => 'AchatController',
        ]);
    }


    #[Route('/new_achat', name: 'app_achat_new', methods: ['GET', 'POST'])]
    public function addAchat(Request $request)
    {
        $achat = new Achat();

        $client_form = $request->request->get('client');
        $produit_form = $request->get('produit');
        
        $client = $this->ClientRepository->find($client_form);
        $produit = $this->ProduitRepository->find($produit_form);


        $achat->setClient($client);
        $achat->setProduit($produit);

        $this->em->persist($achat);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\Panier;
use App\Repository\AchatRepository;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\VarDumper\Cloner\Data;

class AchatController extends AbstractController
{

    private $em;
    private $AchatRepository;
    private $ClientRepository;
    private $ProduitRepository;
    private $PanierRepository;
    private $MagasinRepository;

    public function __construct(AchatRepository $AchatRepository, ClientRepository $ClientRepository, ProduitRepository $ProduitRepository, PanierRepository $PanierRepository, MagasinRepository $MagasinRepository, EntityManagerInterface $em)

    {
        $this->em = $em;
        $this->AchatRepository = $AchatRepository;
        $this->ClientRepository = $ClientRepository;
        $this->ProduitRepository = $ProduitRepository;
        $this->PanierRepository = $PanierRepository;
        $this->MagasinRepository = $MagasinRepository;
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
        $achat->setDate(new \DateTime());

        $this->em->persist($achat);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/get_all_achat', name: 'app_all_achat')]
    public function getAllAchat()
    {
        $achat = $this->AchatRepository->getTousAchat();

        $response = $this->json(
            $achat,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['achat:read', 'achat:read']]
        );
        return $response;
    }



    #[Route('/afficherDetailAchat', name: 'app_achat_by_id')]
    public function getIdDetaile(Request $request)
    {
        $id = $request->get('id');
        $achat = $this->AchatRepository->getAchatById($id);
        $response = $this->json(
            $achat,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'achat:read',
                ],
            ]
        );
        return $response;
    }


    #[Route('/afficher_produit_by_magasin', name: 'app_show_product_by_magasin')]
    public function findByMagasin(Request $request)
    {
        $id = $request->get('id');
        $produit = $this->ProduitRepository->findByMagasin($id);
        $response = $this->json(
            $produit,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'achat:read',
                ],
            ]
        );
        return $response;
    }


    #[Route('/afficher_prix_produit', name: 'app_show_prix_product')]
    public function findPrixProduct(Request $request)
    {
        $id = $request->get('id');
        $produit = $this->ProduitRepository->findPrixProduct($id);
        $response = $this->json(
            $produit,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'achat:read',
                ],
            ]
        );
        return $response;
    }


    #[Route('/new_panier', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function addPanier(Request $request)
    {
        $panier = new Panier();

        $input_client = $request->get('ref_client');
        $input_magasin = (int) $request->get('magasin');
        $input_produit = (int) $request->get('produit');
        $prix = $request->get('prix');
        $quantite = (int) $request->get('quantite');
        $total = $request->get('total');

        $client = $this->ClientRepository->findOneBy(['refClient' => $input_client]);
        $magasin = $this->MagasinRepository->find($input_magasin);
        $produit = $this->ProduitRepository->find($input_produit);


        $panier->setClient($client);
        $panier->setMagazin($magasin);
        $panier->setProduit($produit);
        $panier->setPrixUnitaire($prix);
        $panier->setQuantite($quantite);
        $panier->setSousTotal($total);

        $this->em->persist($panier);
        $this->em->flush();

        $this->AchatRepository->soustraireQuantiteProduit($input_produit,$quantite);

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }


    #[Route('/get_all_Panier', name: 'app_all_panier')]
    public function getAllPanier(Request $request)
    {
        $client = $request->get('ref_client');
        $client_entity = $this->ClientRepository->findOneBy(['refClient' => $client]);
        $panier = $this->AchatRepository->getAllPanier($client_entity->getId());
        

        $response = $this->json(
            $panier,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['achat:read', 'achat:read']]
        );
        return $response;
    }


    #[Route('/transfert_Panier', name: 'app_transfert_panier')]
    public function transfertPanier(Request $request)
    {
        $client = $request->get('ref_client');
        $client_entity = $this->ClientRepository->findOneBy(['refClient' => $client]);
        $panier = $this->AchatRepository->transfertPanier($client_entity->getId());
        

        $response = $this->json(
            $panier,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['achat:read', 'achat:read']]
        );
        return $response;
    }



    #[Route('/delete_Panier', name: 'app_delete_panier')]
    public function deletePanier(Request $request)
    {
        $id = $request->get('id');
       
        $panier = $this->em->getRepository(Panier::class)->find($id);

        $this->em->remove($panier);
        $this->em->flush();

        $this->AchatRepository->additionQuantiteProduit($panier->getProduit()->getId(),$panier->getQuantite());
        

        $response = $this->json(
            $panier,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['achat:read', 'achat:read']]
        );
        return $response;
    }


    #[Route('/vider_Panier', name: 'app_vider_panier')]
    public function viderPanier(Request $request)
    {
        $client = $request->get('ref_client');
        $client_entity = $this->ClientRepository->findOneBy(['refClient' => $client]);
        $panier = $this->AchatRepository->viderPanier($client_entity->getId());
        

        $response = $this->json(
            $panier,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['achat:read', 'achat:read']]
        );
        return $response;
    }
}

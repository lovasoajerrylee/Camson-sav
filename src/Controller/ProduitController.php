<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


#[Route('/produit')]
class ProduitController extends AbstractController
{
    private $em;
    private $ProduitRepository;
    private $MagasinRepository;

    public function __construct(MagasinRepository $MagasinRepository, ProduitRepository $ProduitRepository, EntityManagerInterface $em)

    {
        $this->em = $em;
        $this->ProduitRepository = $ProduitRepository;
        $this->MagasinRepository = $MagasinRepository;
    }
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/getAll', name: 'app_all_produit')]
    public function getAllProduit()
    {
        $client = $this->ProduitRepository->findAll();
        $response = $this->json(
            $client,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['produit:read', 'magasin:read']]
        );
        return $response;
    }

    #[Route('/getId', name: 'app_getProduit_findById')]
    public function getIdDetaile(Request $request)
    {
        $id = $request->get('id');
        $produit = $this->ProduitRepository->find($id);
        $response = $this->json(
            $produit,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'produit:read',
                    'magasin:read'
                ],
            ]
        );
        return $response;
    }
    #[Route('/new', name: 'app_produit_new', methods: ['POST'])]
    public function addProduit(Request $request)
    {
        $produit = new Produit();

        $nom_produit = $request->request->get('nom_produit');
        $reference_produit = $request->get('reference_produit');
        $magasin_id = $request->request->get('magasin_id');
        $magasin = $this->MagasinRepository->find($magasin_id);

        $produit->setNomProduit($nom_produit);
        $produit->setReferenceProduit($reference_produit);
        $produit->setMagasin($magasin);
        $this->em->persist($produit);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/update', name: 'app_update_produit', methods: ['POST'])]
    public function updateProduit(Request $request)
    {
        $id = $request->request->get('id');
        $produit = $this->ProduitRepository->find($id);

        $nom_produit = $request->request->get('nom_produit');
        $reference_produit = $request->get('reference_produit');
        $magasin_id = $request->request->get('magasin_id');
        $magasin = $this->MagasinRepository->find($magasin_id);

        $produit->setNomProduit($nom_produit);
        $produit->setReferenceProduit($reference_produit);
        $produit->setMagasin($magasin);
        $this->em->persist($produit);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/client/delete', name: 'app_produit_delete')]
    public function deleteMagasin(Request $request)
    {
        $id = $request->get('id');
        $produit = $this->ProduitRepository->find($id);
        $this->em->remove($produit);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
}

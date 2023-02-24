<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ListProbleme;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ListProblemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ListProblemeController extends AbstractController
{

    private $em;
    private $ListProblemeRepository;

    public function __construct(ListProblemeRepository $ListProblemeRepository, EntityManagerInterface $em)
    {
        $this->ListProblemeRepository = $ListProblemeRepository;
        $this->em = $em;
    }

    #[Route('/listeProbleme/allListeProbleme', name: 'app_ListeProbleme_all')]
    public function getAllListeProbleme()
    {
        $ListeProbleme = $this->ListProblemeRepository->findAll();
        $response = $this->json(
            $ListeProbleme,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['ListeProbleme:read']]
        );
        return $response;
    }

    #[Route('/listeProbleme/addListeProbleme', name: 'app_addListeProbleme')]
    public function addListeProbleme(Request $request)
    {
        $ListeProbleme = new ListProbleme();

        $listProbleme = $request->request->get("listProbleme");

        $ListeProbleme->setNom($listProbleme);

        $this->em->persist($ListeProbleme);
        $this->em->flush();

        return new JsonResponse([
            "message" => 'Ok',
            "code" => 200,
        ]);
    }
}

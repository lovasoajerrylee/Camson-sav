<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Magasin;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MagasinController extends AbstractController
{

    private $em;

    public function __construct(MagasinRepository $MagasinRepository, EntityManagerInterface $em)
    {
        $this->MagasinRepository = $MagasinRepository;
        $this->em = $em;
        
    }

    #[Route('/magasin', name: 'app_magasin')]
    public function index()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('magasin/index.html.twig');
    }
    #[Route('/magasin/allMagasin', name: 'app_magasin_all_magasin')]
    public function getAllMagasin()
    {
        $magasin = $this->MagasinRepository->findAll();
        $response = $this->json(
            $magasin,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['magasin:read']]
        );
        return $response;
    }

    #[Route('/magasin/get', name: 'app_magasin_get')]
    public function getById(Request $request)
    {
        $idMagasinInput = $request->get('id');
        $magasin = $this->MagasinRepository->find($idMagasinInput);
    
        $response = $this->json(
            $magasin,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['magasin:read']]
        );
        return $response;
    }

    #[Route('/magasin/delete', name: 'app_magasin_delete')]
    public function deleteMagasin(Request $request)
    {
        $idMagasinInput = $request->get('id');
        $magasin = $this->MagasinRepository->find($idMagasinInput);
        $this->em->remove($magasin);
        $this->em->flush();
        
        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
    #[Route('/magasin/add', name: 'app_magasin_add')]
    public function addMagasin(Request $request)
    {
        $magasin = new Magasin();

        $nomMag = $request->request->get('nomMag');
        $nomSocial = $request->request->get('nomSocial');
        $rue = $request->request->get('rue');
        $nomRue = $request->request->get('nomRue');
        $ville = $request->request->get('ville');
        $dateCreation = $request->request->get('dateCreation');
        $email = $request->request->get('email');
        $phoneFix = $request->request->get('phoneFix');
        $dette = $request->request->get('dette');
        $fax = $request->request->get('fax');
        $white = $request->request->get('white');

        $magasin->setFax($fax);
        $magasin->setNomMag($nomMag);
        $magasin->setNomSocial($nomSocial);
        $magasin->setRue($rue);
        $magasin->setNomRue($nomRue);
        $magasin->setVille($ville);
        $magasin->setEmail($email);
        $magasin->setPhoneFix($phoneFix);
        
        if ($dette == null) {
            $magasin->setDette(0);
        } else{
            $magasin->setDette($dette);
        }
        $magasin->setDateCreation(\DateTime::createFromFormat('Y-m-d', $dateCreation));

        if ($white == null) {
            $magasin->setWhite(0);
        } elseif ($white == 1) {
            $magasin->setWhite(1);
        }

        $this->em->persist($magasin);
        $this->em->flush();
        
        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }
    #[Route('/magasin/update', name: 'app_magasin_update')]
    public function updateMagasin(Request $request)
    {
        $idMagasinInput = $request->get('id');
        $magasin = $this->MagasinRepository->find($idMagasinInput);

        $nomMag = $request->request->get('nomMag');
        $nomSocial = $request->request->get('nomSocial');
        $rue = $request->request->get('rue');
        $nomRue = $request->request->get('nomRue');
        $ville = $request->request->get('ville');
        $dateCreation = $request->request->get('dateCreation');
        $email = $request->request->get('email');
        $phoneFix = $request->request->get('phoneFix');
        $dette = $request->request->get('dette');
        $fax = $request->request->get('fax');
        $white = $request->request->get('white');

        $magasin->setFax($fax);
        $magasin->setNomMag($nomMag);
        $magasin->setNomSocial($nomSocial);
        $magasin->setRue($rue);
        $magasin->setNomRue($nomRue);
        $magasin->setVille($ville);
        $magasin->setEmail($email);
        $magasin->setPhoneFix($phoneFix);
        $magasin->setDette($dette);
        $magasin->setDateCreation(\DateTime::createFromFormat('Y-m-d', $dateCreation));

        if ($white == null) {
            $magasin->setWhite(0);
        } elseif ($white == 1) {
            $magasin->setWhite(1);
        }

        $this->em->persist($magasin);
        $this->em->flush();
        
        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/magasin/search', name: 'app_magasin_search')]
    public function searchMagasin(Request $request)
    {
        $param = $request->get('param');
        $magasin = $this->MagasinRepository->searchMagasin($param);
        
        $response = $this->json(
            $magasin,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['magasin:read']]
        );
        return $response;
    }
}

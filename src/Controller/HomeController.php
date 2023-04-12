<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\SavRepository;
use App\Repository\PrioritaireRepository;
use App\Repository\ClientRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    private $em;
    private $MagasinRepository;
    private $ClientRepository;
    private $SavRepository;
    private $PrioritaireRepository;

    public function __construct(
        PrioritaireRepository $PrioritaireRepository,
        ClientRepository $ClientRepository,
        MagasinRepository $MagasinRepository,
        SavRepository $SavRepository,
        EntityManagerInterface $em
    ) {
        $this->SavRepository = $SavRepository;
        $this->MagasinRepository = $MagasinRepository;
        $this->ClientRepository = $ClientRepository;
        $this->PrioritaireRepository = $PrioritaireRepository;
        $this->em = $em;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }
        $countSavAttente = count($this->SavRepository->findBy(['etat' => 0]));
        $countSav = count($this->SavRepository->findAll());
        $countSavEnCours = count($this->SavRepository->findBy(['etat' => 1]));
        $countSavValider = count($this->SavRepository->findBy(['etat' => 2]));
        $countSavTerminer = count($this->SavRepository->findBy(['etat' => 3]));
        $countMagasin = count($this->MagasinRepository->findAll());
        $countClient = count($this->ClientRepository->findAll());
        return $this->render('home/index.html.twig', [
            'countSavAttente' => $countSavAttente,
            'countSavEnCours' => $countSavEnCours,
            'countSavValider' => $countSavValider,
            'countSavTerminer' => $countSavTerminer,
            'countMagasin' => $countMagasin,
            'countClient' => $countClient,
            'countSav' => $countSav,
        ]);
    }

    #[Route('/home/lineChart', name: 'app_home_line_chart')]
    public function lineChart(Request $request)
    {
        $annee = $request->get('annee');
        $sav = [];
        $savAttente = [];
        $savEnCours = [];
        $savValider = [];
        $savTerminer = [];
        $mois = ['01',"02","03","04","05","06","07","08","09","10","11","12"];
        $val = [];
        foreach ($mois as $value) {
            $val[] = $value;
        }
        foreach ($val as $key) {
            $sav[] = count($this->SavRepository->findSavByDate($key, $annee));
            $savAttente[] = count($this->SavRepository->findSavEnAttenteByDate($key, $annee));
            $savEnCours[] = count($this->SavRepository->findSavEnCoursByDate($key, $annee));
            $savValider[] = count($this->SavRepository->findSavValiderByDate($key, $annee));
            $savTerminer[] = count($this->SavRepository->findSavTerminerByDate($key, $annee));
        }
        $response = $this->json(
            [$sav, $savAttente, $savEnCours, $savValider, $savTerminer],
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'sav:read',
                    'messageInSav:read',
                    'magasin:read',
                    'prioritaire:read',
                ],
            ]
        );
        return $response;
    }
}

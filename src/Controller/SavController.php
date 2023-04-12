<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Sav;
use App\Entity\User;
use App\Entity\Probleme;
use App\Entity\Prioritaire;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\SavRepository;
use App\Repository\UserRepository;
use App\Repository\PrioritaireRepository;
use App\Repository\ProblemeRepository;
use App\Repository\ClientRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SavController extends AbstractController
{
    private $em;
    private $MagasinRepository;
    private $ClientRepository;
    private $SavRepository;
    private $UserRepository;
    private $ProblemeRepository;
    private $PrioritaireRepository;

    public function __construct(
        PrioritaireRepository $PrioritaireRepository,
        ProblemeRepository $ProblemeRepository,
        ClientRepository $ClientRepository,
        MagasinRepository $MagasinRepository,
        SavRepository $SavRepository,
        UserRepository $UserRepository,
        EntityManagerInterface $em
    ) {
        $this->SavRepository = $SavRepository;
        $this->MagasinRepository = $MagasinRepository;
        $this->ClientRepository = $ClientRepository;
        $this->ProblemeRepository = $ProblemeRepository;
        $this->PrioritaireRepository = $PrioritaireRepository;
        $this->UserRepository = $UserRepository;
        $this->em = $em;
    }

    #[Route('/sav', name: 'app_sav')]
    public function index(): Response
    {
        // $sav = $this->SavRepository->findAll();
        // $datas=[];
        // foreach($sav as $sa){
        //     $prob=array();
        //     foreach($sa->getProblemes() as $p){
        //         $prob=[
        //             "id"=>$p->getId(),
        //             "objet"=>$p->getProbleme()
        //         ];

        //     }
        //     $datas=[
        //         "id"=>$sa->getId(),
        //         "problemes"=>$prob
        //     ];

        // }
        // dd($datas);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }

        return $this->render('sav/index.html.twig');
    }

    #[Route('/sav/allSav', name: 'app_sav_all_sav')]
    public function getAllSav()
    {
        $sav = $this->SavRepository->findAll();
        $response = $this->json(
            $sav,
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

    #[Route('/sav/allSavByUser', name: 'app_sav_all_sav_user')]
    public function getAllSavByUser(Request $request)
    {
        $idUser = $request->get('idUser');
        $sav = $this->SavRepository->findBy(['user_id' => $idUser]);
        $response = $this->json(
            $sav,
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

    #[Route('/sav/addSav', name: 'app_addSav')]
    public function addSav(Request $request)
    {
        $sav = new Sav();

        $time = new \DateTimeImmutable('now');

        $magasinIdInput = $request->request->get('magasin');
        $clientIdInput = $request->request->get('client');
        $userIdInput = $request->request->get('user');

        $magasinId = $this->MagasinRepository->find($magasinIdInput);
        $clientId = $this->ClientRepository->find($clientIdInput);
        $userId = $this->UserRepository->find($userIdInput);

        $problemeInput = $request->get('probleme');
        $dateAppel = $request->request->get('dateAppel');
        $refSav = $request->request->get('refSav');
        $dateIntervantion = $request->request->get('dateIntervantion');
        $nbEmplacement = $request->request->get('nbEmplacement');
        $intervention = $request->request->get('intervention');
        $niveauIdInput = $request->request->get('niveau');
        $adminCreer = $request->request->get('adminCreer');

        $findNiveau = $this->PrioritaireRepository->find($niveauIdInput);

        $sav->setMagasin($magasinId);
        $sav->setAdminCreer($adminCreer);
        $sav->setUserId($userId);
        $sav->setCreatedAt($time);
        $sav->setClient($clientId);
        $sav->setRefSav($refSav);
        $sav->setNiveau($findNiveau);
        $sav->setDateAppel(\DateTime::createFromFormat('Y-m-d', $dateAppel));
        $sav->setNbEmplacement($nbEmplacement);
        $sav->setEtat(0);
        if ($intervention == null) {
            $sav->setIntervention(0);
        } elseif ($intervention == 1) {
            $sav->setIntervention(1);
        }
        $sav->setDateIntervantion(
            \DateTime::createFromFormat('Y-m-d', $dateIntervantion)
        );

        foreach ($problemeInput as $value) {
            $probleme = new Probleme();
            $probleme->setProbleme($value);
            $sav->addProbleme($probleme);
        }

        $this->em->persist($sav);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/sav/getId', name: 'app_sav_get_id')]
    public function getIdDetaile(Request $request)
    {
        $id = $request->get('id');
        $sav = $this->SavRepository->find($id);
        $response = $this->json(
            $sav,
            200,
            ['Content-Type' => 'appication/json'],
            [
                'groups' => [
                    'sav:read',
                    'client:read',
                    'magasin:read',
                    'prioritaire:read',
                    'problemeInsav:read',
                    'probleme:read',
                ],
            ]
        );
        return $response;
    }

    #[Route('/sav/updateSav', name: 'app_updateSav')]
    public function updateSav(Request $request)
    {
        $idSav = $request->request->get('id');

        $sav = $this->SavRepository->find($idSav);

        $magasinIdInput = $request->request->get('magasin');
        $clientIdInput = $request->request->get('client');

        $magasinId = $this->MagasinRepository->find($magasinIdInput);
        $clientId = $this->ClientRepository->find($clientIdInput);

        $problemeInput = $request->get('probleme');

        $dateAppel = $request->request->get('dateAppel');
        $refSav = $request->request->get('refSav');
        $dateIntervantion = $request->request->get('dateIntervantion');
        $nbEmplacement = $request->request->get('nbEmplacement');
        $intervention = $request->request->get('intervention');
        $niveauIdInput = $request->request->get('niveau');

        $findNiveau = $this->PrioritaireRepository->find($niveauIdInput);

        $sav->setMagasin($magasinId);
        $sav->setClient($clientId);
        $sav->setRefSav($refSav);
        $sav->setNiveau($findNiveau);
        $sav->setDateAppel(\DateTime::createFromFormat('Y-m-d', $dateAppel));
        $sav->setNbEmplacement($nbEmplacement);
        $sav->setEtat(0);

        if ($intervention == null) {
            $sav->setIntervention(0);
        } elseif ($intervention == 1) {
            $sav->setIntervention(1);
        }

        $sav->setDateIntervantion(
            \DateTime::createFromFormat('Y-m-d', $dateIntervantion)
        );

        foreach ($sav->getProblemes() as $value) {
            $problemeDelete = $this->ProblemeRepository->find($value);
            $this->em->remove($problemeDelete);
            $this->em->flush();
        }

        foreach ($problemeInput as $value) {
            $probleme = new Probleme();
            $probleme->setProbleme($value);
            $sav->addProbleme($probleme);
        }

        $this->em->persist($sav);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Ok',
            'code' => 200,
        ]);
    }

    #[Route('/sav/betweenTwoDate', name: 'app_sav_betweenTwoDate')]

    public function betweenTwoDate(Request $request)
    {
        $dateDebutInput = $request->get('dateDebut');
        $dateFinInput = $request->get('dateFin');

        $sav = $this->SavRepository->findSavBeteweenTwoDate(
            $dateDebutInput,
            $dateFinInput
        );

        $response = $this->json(
            $sav,
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
    #[Route('/prioritaire/allPrioritaire', name: 'app_prioritaire_all')]
    public function getAllPrioritaire()
    {
        $prioritaire = $this->PrioritaireRepository->findAll();
        $response = $this->json(
            $prioritaire,
            200,
            ['Content-Type' => 'appication/json'],
            ['groups' => ['prioritaire:read']]
        );
        return $response;
    }
    #[Route('/user/addUser', name: 'app_add_user')]
    public function addUserWeb(
        Request $request,
        UserPasswordHasherInterface $encoder
    ) {
        $username = $request->get('username');
        $password = $request->get('password');
        $rePassword = $request->get('rePassword');
        $email = $request->get('email');
        $role = $request->get('role');

        if ($rePassword != $password) {
            return new JsonResponse([
                'message' => 'Mots de passe pas identique',
                'code' => 209,
            ]);
        } else {
            $user = new User($username);
            $user->setPassword($encoder->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles([$role]);
            $user->setUsername($username);
            $this->em->persist($user);
            $this->em->flush();

            return new JsonResponse([
                'message' => 'Ok',
                'code' => 200,
            ]);
        }
    }
}
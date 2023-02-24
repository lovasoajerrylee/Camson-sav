<?php

namespace App\Controller\Api;

use App\Entity\Sav;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Magasin;
use App\Entity\Probleme;
use App\Entity\Prioritaire;
use App\Entity\ListProbleme;
use App\Repository\SavRepository;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProblemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PrioritaireRepository;
use App\Repository\ListProblemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SavApiController extends ApiController
{
    private $em;
    private $MagasinRepository;
    private $ClientRepository;
    private $SavRepository;
    private $ProblemeRepository;
    private $PrioritaireRepository;
    private $userRepository;
    private $listProblemeRepository;

    public function __construct(
        PrioritaireRepository $PrioritaireRepository,
        ProblemeRepository $ProblemeRepository,
        ClientRepository $ClientRepository,
        MagasinRepository $MagasinRepository,
        SavRepository $SavRepository,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ListProblemeRepository $listProblemeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->SavRepository = $SavRepository;
        $this->MagasinRepository = $MagasinRepository;
        $this->ClientRepository = $ClientRepository;
        $this->ProblemeRepository = $ProblemeRepository;
        $this->PrioritaireRepository = $PrioritaireRepository;
        $this->listProblemeRepository = $listProblemeRepository;
        $this->em = $em;
    }

        //==================== Tout le SAV  ===============================
            #[Route('/api/sav/savByTech/{tech}', name: 'all_sav',methods: ['GET'])]
            public function getSavByUser(Request $request, $tech)
            {
                //$id_user = $request->get('id_user');
                $sav = $this->SavRepository->findBy(['user_id' => $tech]);
                foreach ($sav as $value) {
                    $problems = [];
                    $getAdminCreer = $value->getAdminCreer();
                    $senderName = $this->userRepository->findOneBy(['id' =>$getAdminCreer]);
                    foreach ($value->getProblemes() as $p) {
                        $problems[] = [
                            'id' => $p->getId(),
                            'objet' => $p->getProbleme(),
                        ];
                    }
                    if($senderName == null)
                    {
                        return $this->json([
                            'message' =>" verifier le colonne de sender"
                        ]);
                    } 
                    $datas[] = [
                        'id' => $value->getId(),
                        'sender' =>$senderName->getUsername(),
                        'client_nom' => $value->getClient()->getNomClient(),
                        'client_prenom' => $value->getClient()->getPrenomClient(),
                        'magasin_nom' => $value->getMagasin()->getNomMag(),
                        'date_appel' => $value->getDateAppel(),
                        'date_intervention' => $value->getDateIntervantion(),
                        'nb_deplacement' => $value->getNbEmplacement(),
                        'problemes' => $problems,
                        'niveau' => $value->getNiveau()->getNiveau(),
                        'created_at' => $value->getCreatedAt(),
                        'etat' => $value->getEtat(),
                        'solution' =>$value->getSolution()
                    ];
                    }            
                            return $this->json([
                            'Success' => true,
                            'Code' => 200,
                            'data' => $datas,
                        ]);

        }
        //==================== fin Tout le SAV  ===============================

        //==================== Ajouter SAV ================================

        #[Route('/api/sav/ajoutSav', name: 'addSav',methods: ['POST'])]
        public function addSav(Request $request): JsonResponse
        {
            $request = $this->transformJsonBody($request);
            $time = new \DateTimeImmutable('now');
            $sav = new Sav();
            $magasinIdInput = $request->get('magasin');
            $clientIdInput = $request->get('client');
            $magasinId = $this->MagasinRepository->find($magasinIdInput);
            $clientId = $this->ClientRepository->find($clientIdInput);
            $problemeInput = $request->get('probleme');
            $id_user = $request->get('user_id');
            $user = $this->userRepository->findOneBy(['id' => $id_user]);
            $dateAppel = $request->get('dateAppel');
            $refSav = $request->get('refSav');
            $dateIntervantion = $request->get('dateIntervantion');
            $nbDeplacement = $request->get('nbDeplacement');
            $intervention = $request->get('intervention');
            $niveauIdInput = $request->get('niveau');
            $adminConnect = $request->get('admin_creer');

            $findNiveau = $this->PrioritaireRepository->find($niveauIdInput);
            $sav->setUserId($user);
            $sav->setMagasin($magasinId);
            $sav->setCreatedAt($time);
            $sav->setClient($clientId);
            $sav->setRefSav($refSav);
            $sav->setNiveau($findNiveau);
            $sav->setDateAppel(\DateTime::createFromFormat('Y-m-d', $dateAppel));
            $sav->setNbEmplacement($nbDeplacement);
            $sav->setEtat(0);
            if ($intervention == null) {
                $sav->setIntervention(0);
            } elseif ($intervention == 1) {
                $sav->setIntervention(1);
            }
            $sav->setDateIntervantion(
                \DateTime::createFromFormat('Y-m-d', $dateIntervantion)
            );
            $sav->setDateIntervantion(
                \DateTime::createFromFormat('Y-m-d', $dateIntervantion)
            );
            $sav->setAdminCreer($adminConnect);

            foreach ($problemeInput as $value) {
                $probleme = new Probleme();
                $probleme->setProbleme($value);
                $sav->addProbleme($probleme);
            }

            $this->em->persist($sav);
            $this->em->flush();

            return $this->json(
                [
                    'Success' => true,
                    'Code' => 200,
                    'Message' => 'Successful',
                ],
                200,
                ['Content-Type' => 'appication/json']
            );
        }
        //==================== fin Ajouter sav =====================}

        //========================== Modification etat en cours =============================
        #[Route('/api/sav/sav_EnCours', name: 'modif_etat_cour',methods: ['POST'])]
        public function sav_Encours(Request $request): JsonResponse
        {
            $request = $this->transformJsonBody($request);
            $id = $request->get('id');
            $etat = $request->get('etat');
            $sav = $this->SavRepository->find($id);
            if (!$sav) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'No data',
                ]);
            }
            $sav->setEtat(1);
            $this->em->persist($sav);
            $this->em->flush();
            return new JsonResponse([
                'message' => 'Sav en attente avec success',
                'code' => 200,
            ]);
        }
        //==================== fin modification etat encours=============================

        //========================== Modification etat valider =============================
        #[Route('/api/sav/sav_Valider', name: 'modif_etat_valider',methods: ['POST'])]
        public function sav_Valider(Request $request): JsonResponse
        {
            $request = $this->transformJsonBody($request);
            $id = $request->get('id');
            $etat = $request->get('etat');
            $sav = $this->SavRepository->find($id);
            if (!$sav) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'No data',
                ]);
            }
            $sav->setEtat(2);
            $this->em->persist($sav);
            $this->em->flush();
            return new JsonResponse([
                'message' => 'Sav valider avec success',
                'code' => 200,
            ]);
        }
        //==================== fin modification etat valider =============================

        //========================== Modification etat Terminer =============================
        #[Route('/api/sav/sav_Terminer', name: 'modif_etat_terminer',methods: ['POST'])]
        public function sav_Terminer(Request $request): JsonResponse
        {
            $request = $this->transformJsonBody($request);
            $id = $request->get('id');
            $etat = $request->get('etat');
            $sav = $this->SavRepository->find($id);
            if (!$sav) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'No data',
                ]);
            }
            $sav->setEtat(3);
            $this->em->persist($sav);
            $this->em->flush();
            return new JsonResponse([
                'message' => 'Sav terminer avec success',
                'code' => 200,
            ]);
        }
        //==================== fin modification etat encours=============================

            //========================== Modification etat archiver =============================
            #[Route('/api/sav/sav_Archiver', name: 'modif_etat_archiver',methods: ['POST'])]
            public function sav_Archive(Request $request): JsonResponse
            {
                $request = $this->transformJsonBody($request);
                $id = $request->get('id');
                $sav = $this->SavRepository->find($id);
                if (!$sav) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'No data',
                    ]);
                }
                $sav->setEtat(4);
                $this->em->persist($sav);
                $this->em->flush();
                return new JsonResponse([
                    'message' => 'Sav terminer avec success',
                    'code' => 200,
                ]);
            }
        //==================== fin modification etat archivee=============================

        //==================== add problme by SAV=============================
        #[Route('/api/sav/addProblemByTech', name: 'addProblemByTech',methods: ['POST'])]
        public function addProblemByTech(Request $request): JsonResponse
        {
            //$request = $this->transformJsonBody($request);
            $idSav = $request->get('id');
            $newBlem = $request->get('newProbleme');
            
            $sav = $this->SavRepository->find($idSav);
            $p = new Probleme();

            $listeBlem = new ListProbleme();
            $listeProbleme = $this->listProblemeRepository->findAll();
            $verify_probleme = array();
            foreach ($listeProbleme as $value) {
                $liste= $value->getNom();
                array_push($verify_probleme,$liste);  
                if(in_array($newBlem,$verify_probleme)){
                
                }else{
                    $listeBlem->setNom($newBlem);
                    $this->em->persist($listeBlem);
                    $this->em->flush();
                }
            }
            
            $p->setProbleme($newBlem);
            $sav->addProbleme($p);
            $this->em->persist($sav);
            $this->em->flush();
            return  $this->json([
                'message' => 'probleme ajouté',
                'code' => 200,
            ]);
        }
        //==================== fin add problme by SAV=============================

        //==================== Ajout Solution =============================
        #[Route('/api/sav/addSolution', name: 'addSolution',methods: ['POST'])]
        public function addSolution(Request $request): JsonResponse
        {
            $request = $this->transformJsonBody($request);
            $id = $request->get('id');
            $sav = $this->SavRepository->find($id);
            $solution = $request->get('solution');
            if (!$sav) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'No data',
                ]);
            }
            $sav->setSolution($solution);
            $this->em->persist($sav);
            $this->em->flush();
            return new JsonResponse([
                'message' => 'Solution est ajouté avec success',
                'code' => 200,
            ]);
        }
        //==================== fin Ajout solution =============================

        //==================== get solution ==========================
        #[Route('/api/sav/getSolution/{sav}', name: 'getSolution',methods: ['GET'])]
        public function getSolution(Request $request, $sav): JsonResponse
        {
            $solution = $this->SavRepository->find(['id'=>$sav]);
            $solutions = $solution->getSolution();
            return $this->json([
                'data' =>$solutions,
                'code'=> 200,
            ]);
        }
        //==================== fin get solution =======================
}

<?php
namespace App\Controller\Api;


use App\Entity\ListProbleme;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ListProblemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ListProblemeApiController extends AbstractController
{

    private $em;
    private $ListProblemeRepository;

    public function __construct(ListProblemeRepository $ListProblemeRepository, EntityManagerInterface $em)
    {
        $this->ListProblemeRepository = $ListProblemeRepository;
        $this->em = $em;
        
    }

    //============================= get all probleme ===========================
    #[Route('/api/probleme/getAllProbleme', name: 'api_all_probleme', methods:['GET'])]
    public function getAllProbleme()
    {
        $listeProbleme = $this->ListProblemeRepository->findAll();
        if($listeProbleme == null) {
            return $this->json(
                [
                    "message"=>"pas de donnée"
                ]
            );
        }else{
            return $this->json(
                [
                    "success"=>true,
                    "data"=>$listeProbleme
                ],
                200,
                ['Content-Type' => 'appication/json']
            );
        }
    }
//========================= fin list probleme =============================

//========================= ajout liste de probleme =============================
#[Route('/api/addListProbleme', name: 'api_add_liste_probleme', methods:['POST'])]
public function addNewProblem(Request $request) :JsonResponse
{
    $p = new ListProbleme();
    $newProbeme = $request->request->get("probleme");

    $p->setNom($newProbeme);

    $this->em->persist($p);
    $this->em->flush();
return $this->json([
    "Success"=>true, 
    "Code"=>200, 
    "Message"=>"Successful"],
    200,
    ['Content-Type' => 'appication/json']); 
}
//========================= ajout liste de probleme =============================
}

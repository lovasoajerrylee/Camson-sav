<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }
        return $this->render('accueil/index.html.twig');
    }

    #[Route('/accessDenied', name: 'app_accessDenied')]
    public function accessDenied(): Response
    {
        return $this->render('accessDenied/index.html.twig');
    }
}

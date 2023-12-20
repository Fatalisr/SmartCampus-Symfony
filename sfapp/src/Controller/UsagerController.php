<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsagerController extends AbstractController
{
    #[Route('/usager', name: 'app_usager')]
    public function index(): Response
    {
        return $this->render('usager/usager.html.twig', [
            'controller_name' => 'UsagerController',
        ]);
    }

    #[Route('/usager/salle/{id}', name: 'app_usager_salle')]
    public function usager_salle_id(): Response
    {
        return $this->render('usager/salle.html.twig', [
            'controller_name' => 'UsagerController',
        ]);
    }
}

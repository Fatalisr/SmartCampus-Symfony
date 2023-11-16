<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ReferentController extends AbstractController
{
    #[Route('/referent', name: 'app_referent')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $saRepository = $entityManager->getRepository('App\Entity\SA');
        $roomRepository = $entityManager->getRepository('App\Entity\Room');

        $planAction = $saRepository->findAllPlanAction();
        $inactive = $saRepository->findAllInactive();
        $rooms = $roomRepository->findAll();

        return $this->render("referent/referent.html.twig", [
        'path' => 'src/Controller/ReferentController.php',
        'planAction' => $planAction,
        'inactive' => $inactive,
        'rooms' => $rooms,
        ]);
    }
}

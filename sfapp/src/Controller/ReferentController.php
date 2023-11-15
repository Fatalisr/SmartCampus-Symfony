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
        $repository = $$entityManager->getRepository('App\Entity\SA');

        $planAction = $repository->findAllPlanAction();

        return $this->render("referent/referent.html.twig", [
        'path' => 'src/Controller/ReferentController.php',
        'planAction' => $planAction,
        ]);
    }
}

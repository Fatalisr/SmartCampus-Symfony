<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnicienController extends AbstractController
{
    #[Route('/technicien', name: 'app_technicien')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $interventionRepository = $entityManager->getRepository('App\Entity\Intervention');

        $installations = $interventionRepository->findAllInstallations();
        $maintenances = $interventionRepository->findAllMaintenances();

        return $this->render('technicien/home_tech.html.twig', [
            'maintenances' => $maintenances,
            'installations' => $installations,
        ]);
    }

    #[Route('/technicien/maintenance/{id}', name: 'app_view_maintenance')]
    public function view_maintenance(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $interventionRepository = $entityManager->getRepository('App\Entity\Intervention');


        return $this->render('technicien/maintenance.html.twig', [

        ]);
    }

    #[Route('/technicien/installation/{id}', name: 'app_view_installation')]
    public function view_installation(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $interventionRepository = $entityManager->getRepository('App\Entity\Intervention');


        return $this->render('technicien/installation.html.twig', [

        ]);
    }

}

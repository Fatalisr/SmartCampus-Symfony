<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\MaintenanceForm;
use App\Form\InstallationForm;
use App\Form\InterventionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/technicien/installation/{id}', name: 'app_view_installation')]
    public function view_installation(?int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager =  $doctrine->getManager();

        $interventionRepo = $entityManager->getRepository('App\Entity\Intervention');
        $curInterv = $interventionRepo->find($id);
        $curSA = $curInterv->getSa();

        $form_validInst = $this->createForm(InstallationForm::class);
        $form_validInst->handleRequest($request);

        $dateCourante = new \DateTime();

        if($form_validInst->isSubmitted() && $form_validInst->isValid()){
            $curSA->setState('ACTIF');
            $curInterv->setState("FINIE");
            $curInterv->setEndingDate($dateCourante);

            $entityManager->persist($curSA);
            $entityManager->persist($curInterv);

            $entityManager->flush();

            return $this->redirectToRoute('app_technicien');
        }
        return $this->render('technicien/installation.html.twig',[
            'curSA' => $curSA,
            'installation' => $curInterv,
            'form_validInstal' => $form_validInst,
        ]);

    }
    #[Route('/technicien/maintenance/{id}', name: 'app_view_maintenance')]
    public function view_maintenance(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager =  $doctrine->getManager();

        $interventionRepo = $entityManager->getRepository('App\Entity\Intervention');
        $curInterv = $interventionRepo->find($id);
        $curSA = $curInterv->getSa();

        $form_validMtn = $this->createForm(MaintenanceForm::class);
        $form_validMtn->handleRequest($request);

        if($form_validMtn->isSubmitted() && $form_validMtn->isValid()){
            if($form_validMtn->getData()['valid'] == "true"){
                $curSA->setState('ACTIF');
                $curInterv->setState("FINIE");
                $curInterv->setEndingDate(new \DateTime());
            }
            else{
                $curSA->setState('INACTIF');
                $curInterv->setState("ANNULEE");
            }

            $report = $form_validMtn->get('report')->getData();
            $curInterv->setReport($report);
            $entityManager->persist($curSA);
            $entityManager->persist($curInterv);

            $entityManager->flush();

            return $this->redirectToRoute('app_technicien');
        }
        return $this->render('technicien/maintenance.html.twig',[
            'curSA' => $curSA,
            'maintenance' => $curInterv,
            'form_validMtn' => $form_validMtn,
        ]);
    }

}

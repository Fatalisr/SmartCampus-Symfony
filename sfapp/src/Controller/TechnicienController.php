<?php

namespace App\Controller;

use App\Form\MaintenanceForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnicienController extends AbstractController
{
    #[Route('/technicien', name: 'app_technicien')]
    public function index(): Response
    {
        return $this->render('technicien/home_tech.html.twig', [
            'controller_name' => 'TechnicienController',
        ]);
    }
    #[Route('/technicien/maintenance/{id}', name: 'mainteannce')]
    public function maintenance(?int $id, ManagerRegistry $doctrine, Request $request) : Response
    {
        $entityManager =  $doctrine->getManager();
        $saRepo = $entityManager->getRepository('App\Entity\SA');
        $curSA = $saRepo->find($id);
        $maintenance = $saRepo->findMaintenanceBySAId($curSA);

        $form_validMtn = $this->createForm(MaintenanceForm::class);
        $form_validMtn->handleRequest($request);

        $dateCourante = new \DateTime();


        if($form_validMtn->isSubmitted() && $form_validMtn->isValid()){
            if($form_validMtn->get('valid')->getData() == 'true'){
                $curSA->setState('ACTIF');
            }else{
                $curSA->setState('INACTIF');
            }
            $maintenance->setEndingDate($dateCourante);

            $entityManager->persist($curSA);
            $entityManager->persist($maintenance);

            $entityManager->flush();

            return $this->redirectToRoute('app_technicien');
        }


        return $this->render('technicien/maintenance.html.twig',[
            'curSA' => $curSA,
            'maintenance' => $maintenance,
            'form_validMtn' => $form_validMtn,
        ]);
    }
}

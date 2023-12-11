<?php

namespace App\Controller;

use App\Form\InterventionFormType;
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

    #[Route('/technicien/installation/{id}', name: 'app_view_installation')]
    public function view_installation(?int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $saRepo = $entityManager->getRepository('App\Entity\SA');
        $curSA = $saRepo->find($id);
        $installation = $saRepo->findInstallationBySAId($curSA);

        $form_validMtn = $this->createForm(InterventionFormType::class);
        $form_validMtn->handleRequest($request);

        $dateCourante = new \DateTime();

        if($form_validMtn->isSubmitted() && $form_validMtn->isValid()) {

            $curSA->setState('ACTIF');
            $installation->setEndingDate($dateCourante);
            $entityManager->persist($curSA);
            $entityManager->persist($installation);
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien');
        }
        return $this->render('technicien/installation.html.twig',[
            'curSA' => $curSA,
            'installation' => $installation,
            'form_validInstal' => $form_validMtn,
        ]);

    }
}

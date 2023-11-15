<?php

namespace App\Controller;

use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ReferentController extends AbstractController
{
    #[Route('/referent', name: 'app_referent')]
    public function index(): Response
    {
        return $this->render("referent.html.twig",[
            'path' => 'src/Controller/ReferentController.php',
        ]);
    }

    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $nom = $sa->getName();
        $salle = $sa->getCurrentRoom()->getName();
        $etat = $sa->getState();

        return $this->render("referent/sa.html.twig",[
            'nom' => $nom,
            'salle' => $salle,
            'etat' => $etat,
        ]);
    }
}

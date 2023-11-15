<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ReferentController extends AbstractController
{
    #[Route('/referent', name: 'app_referent')]
    public function index(): Response
    {
        return $this->render("referent/home_ref.html.twig",[
            'path' => 'src/Controller/ReferentController.php',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\SA;
use App\Repository\RoomRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Bundle\FixturesBundle\Fixture;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\NouveauSaForm;

use Symfony\Component\HttpFoundation\Request;


class ReferentController extends AbstractController
{
    #[Route('/referent', name: 'app_referent')]
    public function index(): Response
    {
        return $this->render("referent/referent.html.twig",[
            'path' => 'src/Controller/ReferentController.php',
        ]);
    }

    #[Route('/referent/nouveausa', name: 'nouveau_SA')]
    public function NouveauSA(Request $request, ManagerRegistry $doctrine): Response
    {


        $form = $this->createForm(NouveauSaForm::class);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $sa = new SA();
            $sa->setName($form->get('name')->getData());


            $entityManager = $doctrine->getManager();
            if($form->get('currentRoom')->getData())
            {
                $sa->setState("A_INSTALLER");
            }
            else
            {
                $sa->setState("INACTIF");
            }
            $sa->setCurrentRoom($form->get('currentRoom')->getData());


            //Ajoute à la base de donnée
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent', [
            ]);

        }

        return $this->render("referent/nouveausa.html.twig",[
            'form' => $form,
        ]);
    }
}

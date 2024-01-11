<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\SA;
use App\Form\choisirSalleUsagerForm;
use App\Repository\SARepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UsagerController extends AbstractController
{
    /* --------------------------------------------------------- */
    /*                    PAGE D'ACCEUIL USAGER                  */
    /* --------------------------------------------------------- */
    #[Route('/usager/{id?}', name: 'app_usager')]
    public function index(?int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        // Manager doctrine
        $entityManager = $doctrine->getManager();

        // Repository
        $roomRepo = $entityManager->getRepository(Room::class);
        $saRepo = $entityManager->getRepository(SA::class);

        // Formulaire pour le choix de la salle a afficher
        $form = $this->createForm(choisirSalleUsagerForm::class);
        $form->handleRequest($request);

        // gestion des variables de salle et SA en fonction de la salle choisi
        if($id == null){ // cas ou aucune salle n'a été choisi
            $room = null;
            $sa = null;
        }else{
            $room = $roomRepo->find($id);
            $sa = $saRepo->findOneBy(['currentRoom' => $room->getId()]);
        }

        // Gestion du formulaire de choix de la salle
        if($form->isSubmitted() && $form->isValid()){
            $idRoom = $form->get('room')->getData()->getId();
            return $this->redirectToRoute('app_usager', ['id' => $idRoom]);
        }

        return $this->render('usager/usager.html.twig', [
            'sa' => $sa,
            'room' => $room,
            'form' => $form,
        ]);
    }
}

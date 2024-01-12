<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\SA;
use App\Form\choisirSalleUsagerForm;
use App\Repository\SARepository;
use App\Service\ConnexionRequetesAPI;
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
    public function index(?int $id, ManagerRegistry $doctrine, Request $request,ConnexionRequetesAPI $api): Response
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
        $roomRepo = $entityManager->getRepository(Room::class);
        $saRepo = $entityManager->getRepository(SA::class);

        $form = $this->createForm(choisirSalleUsagerForm::class);
        $form->handleRequest($request);

        if($id == null)
        {
            $room = null;
            $sa = null;
        }
        else
        {
            $room = $roomRepo->find($id);
            $sa = $saRepo->findOneBy(['currentRoom' => $room->getId()]);
            $donnees = [];
            $donnees = array_merge($donnees,json_decode($api->getlastCaptures(1,$room->getName(),"hum")));
            $donnees = array_merge($donnees,json_decode($api->getlastCaptures(1,$room->getName(),"temp")));
            $donnees = array_merge($donnees,json_decode($api->getlastCaptures(1,$room->getName(),"co2")));
        }
        // Gestion du formulaire de choix de la salle
        $meteo = json_decode($api->getWeather(),true);
        if($form->isSubmitted() && $form->isValid()){
            $idRoom = $form->get('room')->getData()->getId();
            return $this->redirectToRoute('app_usager', ['id' => $idRoom]);
        }


        if($id == null)
        {
            return $this->render('usager/usager.html.twig', [
                'sa' => $sa,
                'room' => $room,
                'form' => $form,
                'meteo' => $meteo,
            ]);
        }
        else
        {
            return $this->render('usager/usager.html.twig', [
                'sa' => $sa,
                'room' => $room,
                'form' => $form,
                'meteo' => $meteo,
                'donnees' => $donnees,
            ]);
        }
    }
}

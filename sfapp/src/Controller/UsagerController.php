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
    #[Route('/usager/{id?}', name: 'app_usager')]
    public function index(?int $id, ManagerRegistry $doctrine, Request $request,ConnexionRequetesAPI $api): Response
    {
        $entityManager = $doctrine->getManager();

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
            $donnees = json_decode($api->getlastCaptures(3,$room->getName()));
            //var_dump($donnees);
        }

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

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




        //Gestion des conseils
        $conseils = [];
        if($id != null) {

            $tempINT = null;
            $tempEXT = $meteo['current']['temperature_2m'];
            $coINT = 0;
            $humINT = 0;
            $date = 0;


            foreach ($donnees as $donnee) {
                if ($donnee->nom == 'temp') {
                    $tempINT = (float)$donnee->valeur;
                } elseif ($donnee->nom == 'hum') {
                    $humINT = (float)$donnee->valeur;
                    $date = $donnee->dateCapture;
                } elseif ($donnee->nom == 'co2') {
                    $coINT = (float)$donnee->valeur;
                }
            }

            $month = (int)substr($date, 5, 2);

            $intervalleinf = 17;
            $intervallesup = 21;
            if ($month >= 6 and $month <= 8) {
                $intervallesup = 26;
            }

            if($tempINT != null)
            {
                if (($tempINT > $intervallesup and $tempEXT < $intervalleinf)
                    or ($tempINT < $intervalleinf and $tempEXT > $intervallesup)) {
                    array_push($conseils, "La température intérieur n'est pas optimal mais ouvrir les fenêtres et la porte permettrai de revenir à une température convenable.");
                    if ($month >= 10 or $month <= 3) {
                        array_push($conseils, "Il faut aussi éteindre le chauffage.");
                    }
                } elseif ($tempINT < $intervalleinf and $tempEXT < $intervalleinf) {
                    array_push($conseils, "La température est tros basse, il faut fermé les fenêtres et la porte.");
                    if ($month >= 10 or $month <= 3) {
                        array_push($conseils, "Il faut aussi allumer le chauffage si il est éteint.");
                    }
                } elseif ($tempINT > $intervallesup and $tempEXT > $intervallesup) {
                    array_push($conseils, "La température est tros élevé, il faut fermé les fenêtres, les volets et ouvrir la porte du couloir.");
                    if ($month >= 10 or $month <= 3) {
                        array_push($conseils, "Il faut aussi éteindre le chauffage si il est allumé.");
                    }
                } elseif ($tempEXT > 30) {
                    array_push($conseils, "La température exterieur est tros élevé, il faut fermé les fenêtres, les volets et ouvrir la porte du couloir");
                    if ($month >= 10 or $month <= 3) {
                        array_push($conseils, "Il faut aussi éteindre le chauffage si il est allumé.");
                    }
                } elseif ($humINT > 70 and $coINT > 1500) {
                    array_push($conseils, "Il faut ouvrir les fenêtres pour rétablir la qualité de l'air à l'intérieur de la salle.");
                }
            }

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
                'conseils' => $conseils,
            ]);
        }
    }
}

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




        //Gestion des conseils
        $conseils = [];
        if($id != null) {

            $tempINT = null;
            $tempEXT = $meteo['current']['temperature_2m'];
            $weather = $meteo['current']['weather_code'];

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


                if($tempINT  > $intervalleinf and $tempINT < $intervallesup)
                {
                    if($humINT > 70 and $coINT > 1000)
                    {
                        array_push($conseils, "Le taux de co2 et l'humidité sont trop élevé, il serait judicieux d'aérer en ouvrant les fenêtres et les portes");
                    }
                    elseif ($humINT > 70)
                    {
                        array_push($conseils, "L'humidité est trop élevé, il serait judicieux d'aérer en ouvrant les fenêtres et les portes");
                    }
                    elseif ($coINT > 1000)
                    {
                        array_push($conseils, "L'humidité est trop élevé, il serait judicieux d'aérer en ouvrant les fenêtres et les portes");
                    }
                }
                elseif($tempINT < $intervalleinf)
                {
                    if($humINT > 70 and $coINT > 1000)
                    {
                        array_push($conseils, "Il faut aérer la pièce afin de diminuer le taux de CO2 et le taux d'humidité. Ouvrez la / les porte(s).");
                    }
                    elseif ($humINT > 70)
                    {
                        array_push($conseils, "Il faut aérer la pièce afin de diminuer le taux d'humidité. Ouvrez la / les porte(s).");
                    }
                    elseif ($coINT > 1000)
                    {
                        array_push($conseils, "Il faut aérer la pièce afin de diminuer le taux de CO2. Ouvrez la / les porte(s).");
                    }
                    else
                    {
                        array_push($conseils, "Allumez ou augmentez le chauffage pour augmenter la température de la salle.Vérifiez que les fenêtres sont fermées.");
                    }
                }
                elseif ($tempINT > $intervallesup)
                {
                    if ($humINT > 70)
                    {
                        array_push($conseils, "Une température élevée et un taux d'humidité élevé engendre un risque d'inconfort et de création de moisissure. Il est recommandé d'aérer la pièce en ouvrant la porte et les fenêtres.");
                    }
                    elseif ($coINT > 1000)
                    {
                        array_push($conseils, "Il faut aérer la pièce afin de diminuer le taux de CO2. Ouvrez la / les porte(s).");
                    }
                    elseif ($tempINT < $tempEXT)
                    {
                        array_push($conseils, "La température intérieure élevée est sûrement dûe à la température extérieure élevée. Afin d'essayer de la réduire, éteignez le chauffage, fermez fenêtres et volets et ouvrez la porte afin d'évacuer la chaleur de la salle.");
                    }
                    else
                    {
                        array_push($conseils, "La température intérieure élevée peut être réduite en s'appuyant sur la température extérieur inférieure. Eteignez le chauffage, ouvrez les fenêtres et ouvrez la porte afin de créer un courant d'air frais.");
                    }
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

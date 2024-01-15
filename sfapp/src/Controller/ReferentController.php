<?php

namespace App\Controller;
use App\Entity\Intervention;
use App\Entity\SA;
use App\Entity\User;
use App\Form\InterventionFormType;
use App\Form\MaintenanceForm;
use App\Repository\RoomRepository;
use App\Service\ConnexionRequetesAPI;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Form\changerSalleForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NouveauSaForm;
use Symfony\Component\HttpFoundation\Request;


class ReferentController extends AbstractController
{
    /* --------------------------------------------------------- */
    /*                      PAGE D'ACCUEIL                       */
    /* --------------------------------------------------------- */
    #[Route('/referent', name: 'app_referent')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $entityManager = $doctrine->getManager(); // Manager doctrine

        // instentiation des repository de salle et de SA
        $saRepository = $entityManager->getRepository('App\Entity\SA');
        $roomRepository = $entityManager->getRepository('App\Entity\Room');
        $InterventionRepository = $entityManager->getRepository('App\Entity\Intervention');

        // Liste des SA repartis par état pour les filtres
        $actif = $saRepository->findAllActif();
        $maintenance = $saRepository->findAllMaintenance();
        $installer = $saRepository->findAllInstaller();
        $inactive = $saRepository->findAllInactive();

        $rooms = $roomRepository->findAll(); // Liste des salles
        $forms = []; //Liste pour le stockage des instances de formulaire de changement de salle
        $nbForms = sizeof($inactive) + sizeof($actif) + sizeof($installer);

        for ($i = 0; $i < $nbForms; $i++){
            // Creation de l'instance
            $form = $this->createForm(changerSalleForm::class);
            $form->handleRequest($request);

            // Gestion du formulaire
            if ($form->isSubmitted() && $form->isValid()) {

                // Creation d'une instance de SA dont l'id correspond a celui remonté par le formulaire
                $curSa = $saRepository->find($form->get('sa_id')->getData());

                $interventionInstallation = $InterventionRepository->findOneBySAAndCurrent($curSa);

                if($curSa->getState() != "A_INSTALLER") {

                    if($interventionInstallation != null){
                        $curSa->setState("A_INSTALLER");
                        $interventionInstallation->setType_I("INSTALLATION");
                        $interventionInstallation->setMessage("Déplacement du " . $curSa->getName() . " de la salle " . $curSa->getOldRoom()->getName() . " en " . $form->get('newRoom')->getData()->getName());
                    }
                    else {
                        $curSa->setState("A_INSTALLER");
                        $interventionInstallation = new Intervention();
                        $interventionInstallation->setType_I("INSTALLATION");
                        $interventionInstallation->setState("EN_COURS");
                        $interventionInstallation->setStartingDate(new \DateTime());
                        if($curSa->getCurrentRoom() != null) {
                            $interventionInstallation->setMessage("Déplacement du " . $curSa->getName() . " de la salle ".$curSa->getCurrentRoom()->getName()." en " . $form->get('newRoom')->getData()->getName());
                        }
                        else{
                            $interventionInstallation->setMessage("Installation du " . $curSa->getName() . " en " . $form->get('newRoom')->getData()->getName());
                        }
                        $interventionInstallation->setSa($curSa);
                    }

                    $curSa->setOldRoom($curSa->getCurrentRoom());

                    $entityManager->persist($interventionInstallation);
                }
                else{
                    if($curSa->getOldRoom() != null) {
                        $interventionInstallation->setMessage("Déplacement du " . $curSa->getName() . " de la salle " . $curSa->getOldRoom()->getName() . " en " . $form->get('newRoom')->getData()->getName());
                    }
                    else{
                        $interventionInstallation->setMessage("Installation du ".$curSa->getName()." en ".$form->get('newRoom')->getData()->getName());
                    }
                }
                $curSa->setCurrentRoom($form->get('newRoom')->getData());
                $entityManager->persist($curSa);
                $entityManager->flush();
                return $this->redirectToRoute('app_referent',[]);
            }
            $forms[] = $form->createView();
        }


        return $this->render("referent/referent.html.twig", [
        'path' => 'src/Controller/ReferentController.php',
        'actif' => $actif,
        'maintenance' => $maintenance,
        'installer' => $installer,
        'inactive' => $inactive,
        'rooms' => $rooms,
        'forms' => $forms,
        'countFormActive' => sizeof($actif),
        'countFormInstaller' => sizeof($installer)
        ]);
    }


    /* --------------------------------------------------------- */
    /*                   PAGE DE DETAIL DU SA                    */
    /* --------------------------------------------------------- */
    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine,Request $request,  ConnexionRequetesAPI $requetesAPI): Response
    {
        date_default_timezone_set('Europe/Paris');


        $entityManager = $doctrine->getManager();
        // Reccuperation du SA avec l'id de la route
        $sa = $entityManager->find(SA::class,$id);
        $room = $sa->getCurrentRoom()->getName();

        $today = date("Y-m-d"); //Genère la date d'ajourd'hui
        $yesterday = date('Y-m-d', time() + (60 * 60 * 24)*-1 );  // Genère la date d'hier
        $lastWeek = date('Y-m-d', time() + (60 * 60 * 24 * -7) ); // Genère la date de la semain dernière
        // Récupère les captures d'hier à aujourd'hui
        $reponseCO2T = $requetesAPI->getIntervalCaptures($yesterday,$today,$room,"co2");
        $reponseHUMT = $requetesAPI->getIntervalCaptures($yesterday,$today,$room,"hum");
        $reponseTEMPT = $requetesAPI->getIntervalCaptures($yesterday,$today,$room,"temp");

        // Récupère les captures depuis la semaine dernière
        $reponseCO2LW = $requetesAPI->getIntervalCaptures($lastWeek,$today,$room,"co2");
        $reponseHUMLW = $requetesAPI->getIntervalCaptures($lastWeek,$today,$room,"hum");
        $reponseTEMPLW = $requetesAPI->getIntervalCaptures($lastWeek,$today,$room,"temp");



        // Creation d'un intsance de formulaire pour les demandes de maintenances
        $form = $this->createForm(InterventionFormType::class);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Creation de l'instance de maintennance
            $newMaintenance = new Intervention();
            $newMaintenance->setMessage($form->get('message')->getData()); // reccuperation du message du referent
            $newMaintenance->setStartingDate(date_create(date("Y-m-d")));
            $newMaintenance->setSa($sa);
            $newMaintenance->setType_I("MAINTENANCE");
            $newMaintenance->setState("EN_COURS");
            $sa->setState("MAINTENANCE"); //Changement de l'état du SA en conséquence

            // Envoie de l'Intervention de type MAINTENANCE dans la BD
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newMaintenance);
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent', [
            ]);
        }
        return $this->render("referent/sa.html.twig",[
            'sa' => $sa,
            'form' => $form,
            'donneesCO2T' => $reponseCO2T,
            'donneesHUMT' => $reponseHUMT,
            'donneesTEMPT' => $reponseTEMPT,
            'donneesCO2LW' => $reponseCO2LW,
            'donneesHUMLW' => $reponseHUMLW,
            'donneesTEMPLW' => $reponseTEMPLW,
        ]);
    }

    /* --------------------------------------------------------- */
    /*                 PAGE DE CREATION D'UN SA                  */
    /* --------------------------------------------------------- */
    #[Route('/referent/nouveausa', name: 'nouveau_SA')]
    public function NouveauSA(Request $request, ManagerRegistry $doctrine): Response
    {
        // Creation de l'instence de formulaire
        $form = $this->createForm(NouveauSaForm::class);
        $form->handleRequest($request);

        // Gestion du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Creation de l'instence du nouveau SA
            $sa = new SA();
            $sa->setName($form->get('name')->getData());

            // Manager doctrine
            $entityManager = $doctrine->getManager();

            // Creation d'une intervention si le SA a été assigné a une salle lors de ca creation
            if($form->get('currentRoom')->getData())
            {
                $sa->setCurrentRoom($form->get('currentRoom')->getData());
                $sa->setState("A_INSTALLER");
                $name = $sa->getName();
                $room = $sa->getCurrentRoom()->getName();
                $installationSA = new Intervention();
                $installationSA->setState("EN_COURS");
                $installationSA->setSa($sa);
                $installationSA->setMessage("Installation du $name en $room");
                $installationSA->setStartingDate(date_create(date("m.d.y")));
                $installationSA->setType_I("INSTALLATION");
                $entityManager->persist($installationSA);
            }
            else
            {
                $sa->setState("INACTIF");
            }

            // Set de la salle : si aucune salle n'a été renseigné, currentRoom = null
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

    /* --------------------------------------------------------- */
    /*              RETRAIT D'UN SA DU PLAN D'ACTION             */
    /* --------------------------------------------------------- */
    #[Route('/referent/delete_SA/{id}', name: 'delete_sa')]
    public function delete_sa(?int $id, ManagerRegistry $doctrine): Response
    {
        // Manager doctrine
        $entityManager = $doctrine->getManager();

        // Reccuperation du SA avec l'id de la route
        $sa = $entityManager->find(SA::class, $id);
        $sa->setOldRoom($sa->getCurrentRoom());
        $sa->setCurrentRoom(null);
        $sa->setState("INACTIF");

        // Creation de l'intervention correspondante
        $InterventionRepo = $doctrine->getRepository("App\Entity\Intervention");
        $interventionOld = $InterventionRepo->findOneBySAAndCurrent($sa);
        $interventionOld->setEndingDate(new \DateTime());
        $interventionOld->setState("ANNULEE");

        $intervention = new Intervention();
        $intervention->setMessage("Retirer le ".$sa->getName()." de la salle ".$sa->getOldRoom()->getName());
        $intervention->setStartingDate(new \DateTime());
        $intervention->setSa($sa);
        $intervention->setType_I("MAINTENANCE");
        $intervention->setState("EN_COURS");

        $entityManager->persist($intervention);
        $entityManager->persist($sa);
        $entityManager->flush();

        return $this->redirectToRoute('app_referent', [
        ]);
    }

    /* --------------------------------------------------------- */
    /*                  PAGE DU PLAN DES SALLES                  */
    /* --------------------------------------------------------- */
    #[Route('/referent/plan', name: 'plan_salles')]
    public function plan_salles(ManagerRegistry $doctrine): Response
    {
        // Manager doctrine
        $entityManager = $doctrine->getManager();

        // Repository des salles
        $roomRepository = $entityManager->getRepository('App\Entity\Room');

        // Liste des salles en fonction de leur étage
        $roomRDC = $roomRepository->getRoomRDC();
        $roomFloor1 = $roomRepository->getRoomFloor(1);
        $roomFloor2 = $roomRepository->getRoomFloor(2);
        $roomFloor3 = $roomRepository->getRoomFloor(3);

        // Creation d'un attribut SA qui porte le SA assigné a la salle
        foreach ($roomRDC as $room){
            $sa = $entityManager->getRepository(SA::class)->findOneBy(['currentRoom' => $room]);
            $room->sa = $sa;
        }
        foreach ($roomFloor1 as $room){
            $sa = $entityManager->getRepository(SA::class)->findOneBy(['currentRoom' => $room]);
            $room->sa = $sa;
        }
        foreach ($roomFloor2 as $room){
            $sa = $entityManager->getRepository(SA::class)->findOneBy(['currentRoom' => $room]);
            $room->sa = $sa;
        }
        foreach ($roomFloor3 as $room){
            $sa = $entityManager->getRepository(SA::class)->findOneBy(['currentRoom' => $room]);
            $room->sa = $sa;
        }

        return $this->render("referent/plan_salles.html.twig", [
            'roomRDC' => $roomRDC,
            'roomFloor1' => $roomFloor1,
            'roomFloor2' => $roomFloor2,
            'roomFloor3' => $roomFloor3,
        ]);
    }

    /* --------------------------------------------------------- */
    /*                    SUPPRESSION D'UN SA                    */
    /* --------------------------------------------------------- */
    #[Route('/referent/delete_SA_base/{id}', name: 'delete_sa_base')]
    public function delete_sa_base(?int $id, ManagerRegistry $doctrine): Response
    {
        // Manager doctrine
        $entityManager = $doctrine->getManager();

        // Repository des interventions
        $interventionRepo = $entityManager->getRepository("App\Entity\Intervention");
        $sa = $entityManager->find(SA::class, $id);
        $interventions = $interventionRepo->findOneBySA($sa);

        // Suppression des interventions correspondant au SA
        foreach ($interventions as $intervention){
            $entityManager->remove($intervention);
            $entityManager->flush();
        }

        // Suppression du SA de la base
        $entityManager->remove($sa);
        $entityManager->flush();
        return $this->redirectToRoute('app_referent', [
        ]);
    }

    /* --------------------------------------------------------- */
    /*           PAGE DE L'HISTORIQUE DES INTERVENTIONS          */
    /* --------------------------------------------------------- */
    #[Route('/referent/historique', name: 'historique')]
    public function historique(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();// Manager doctrine

        // Repository des interventions
        $interventionRepo = $entityManager->getRepository(Intervention::class);
        $userRepo = $entityManager->getRepository(User::class);
        $interventions = $interventionRepo->findAll();



        $users = $userRepo->findAll();
        $techniciens = [];
        foreach ($users as $user){
            foreach($user->getRoles() as $role){
                if($role == 'ROLE_TECHNICIEN'){
                    $techniciens[] = $user->getUsername();
                }
            }
        }


        return $this->render("referent/historique.html.twig", [
            'interventions' => $interventions,
            'techniciens' => $techniciens,
        ]);
    }

    #[Route('/referent/saInt/{id}', name: 'intervention_sa')]
    public function intervention_sa(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $interventionRepo = $entityManager->getRepository(Intervention::class);
        $curInterv = $interventionRepo->findOneBySAAndCurrent($sa);
        $message = $curInterv->getMessage();

        $form_validMtn = $this->createForm(MaintenanceForm::class);
        $form_validMtn->handleRequest($request);
        if($form_validMtn->isSubmitted() && $form_validMtn->isValid()){
            if ($form_validMtn->getData()['valid'] == "true") {
                $report = $form_validMtn->get('report')->getData();
                $curInterv->setMessage($report);
            } else {
                if($sa->getState() == "MAINTENANCE"){
                    $sa->setState('ACTIF');
                }
                else {
                    if($sa->getOldRoom() != null) {
                        $sa->setCurrentRoom($sa->getOldRoom());
                        $sa->setOldRoom(null);
                        $sa->setState('ACTIF');
                    }
                    else{
                        $sa->setCurrentRoom(null);
                        $sa->setState('INACTIF');
                    }
                }
                $curInterv->setState("ANNULEE");
            }
            $entityManager->persist($sa);
            $entityManager->persist($curInterv);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent');
        }
        return $this->render("/referent/installation.html.twig",[
            'sa' => $sa,
            'form_validMtn' => $form_validMtn,
            'message' => $message,
        ]);
    }

}


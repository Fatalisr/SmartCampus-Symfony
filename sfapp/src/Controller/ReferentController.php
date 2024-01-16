<?php

namespace App\Controller;
use App\Entity\Intervention;
use App\Entity\SA;
use App\Entity\User;
use App\Form\InterventionFormType;
use App\Form\MaintenanceForm;
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

        // Instanciation des repository ROOM, SA et INTERVENTIONS
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
                print($curSa->getState()."\n");
                $interventionInstallation = $InterventionRepository->findOneBySAAndCurrent($curSa);

                if($curSa->getState() != "A_INSTALLER") {

                    // Modification de l'intervention au cas ou le SA est déjà en cours d'interventions
                    if($interventionInstallation != null){
                        $curSa->setState("A_INSTALLER");
                        $interventionInstallation->setType_I("INSTALLATION");
                        $interventionInstallation->setMessage("Déplacement du " . $curSa->getName() . " de la salle " . $curSa->getOldRoom()->getName() . " en " . $form->get('newRoom')->getData()->getName());
                    }
                    // Création d'une intervention
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
                        $curSa->setOldRoom($curSa->getCurrentRoom());
                        $interventionInstallation->setSa($curSa);
                    }
                    $entityManager->persist($interventionInstallation);
                }
                else{
                    // Définit le message par défaut de l'intervention de type INSTALLATION
                    if($curSa->getOldRoom() != null) {
                        $interventionInstallation->setMessage("Déplacement du " . $curSa->getName() . " de la salle " . $curSa->getOldRoom()->getName() . " en " . $form->get('newRoom')->getData()->getName());
                    }
                    else{
                        $interventionInstallation->setMessage("Installation du ".$curSa->getName()." en ".$form->get('newRoom')->getData()->getName());
                    }
                    $entityManager->persist($interventionInstallation);
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
    public function view_sa(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {

        $entityManager = $doctrine->getManager(); // Manager doctrine

        // Récuperation du SA avec l'id de la route
        $sa = $entityManager->find(SA::class,$id);

        // Création d'un intsance de formulaire pour les demandes de maintenances
        $form = $this->createForm(InterventionFormType::class);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Creation de l'instance de maintennance
            $newMaintenance = new Intervention();
            $newMaintenance->setMessage($form->get('message')->getData()); // reccuperation du message du referent
            date_default_timezone_set('UTC');
            $newMaintenance->setStartingDate(date_create(date("m.d.y")));
            $newMaintenance->setSa($sa);
            $newMaintenance->setType_I("MAINTENANCE");
            $newMaintenance->setState("EN_COURS");
            $sa->setState("MAINTENANCE");

            // Envoie de la maintenance dans la BDD
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
        ]);
    }

    /* --------------------------------------------------------- */
    /*                 PAGE DE CREATION D'UN SA                  */
    /* --------------------------------------------------------- */
    #[Route('/referent/nouveausa', name: 'nouveau_SA')]
    public function NouveauSA(Request $request, ManagerRegistry $doctrine): Response
    {
        // Création de l'instence de formulaire
        $form = $this->createForm(NouveauSaForm::class);
        $form->handleRequest($request);

        // Gestion du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Creation de l'instence du nouveau SA
            $sa = new SA();
            $sa->setName($form->get('name')->getData());

            // Manager doctrine
            $entityManager = $doctrine->getManager();

            // Creation d'une intervention si le SA a été assigné à une salle lors de sa création
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

            //Ajout à la base de donnée
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

        // Réccuperation du SA avec l'id de la route
        $sa = $entityManager->find(SA::class, $id);
        $sa->setOldRoom($sa->getCurrentRoom());
        $sa->setCurrentRoom(null);
        $sa->setState("INACTIF");

        // Création de l'intervention correspondante
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

        // Création d'un attribut SA qui porte le SA assigné à la salle
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

        // Récupération des utilisateurs ayant le rôle TECHNICIEN
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
        // Récupération du SA via l'id de la route
        $sa = $entityManager->find(SA::class,$id);
        $interventionRepo = $entityManager->getRepository(Intervention::class);
        // Récupération de l'intervention correspondante
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
        return $this->render("referent/saInt.html.twig",[
            'sa' => $sa,
            'form_validMtn' => $form_validMtn,
            'message' => $message,
        ]);
    }

}

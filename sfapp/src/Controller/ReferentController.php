<?php

namespace App\Controller;
use App\Entity\Intervention;
use App\Entity\SA;
use App\Entity\User;
use App\Form\InterventionFormType;
use App\Repository\RoomRepository;
use Cassandra\Date;
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

        // Liste des SA repartis par état pour les filtres
        $actif = $saRepository->findAllActif();
        $maintenance = $saRepository->findAllMaintenance();
        $installer = $saRepository->findAllInstaller();
        $inactive = $saRepository->findAllInactive();

        $rooms = $roomRepository->findAll(); // Liste des salles

        $forms = []; //Liste pour le stockage des instances de formulaire de changement de salle

        $nbForms = sizeof($inactive) + sizeof($actif); // Nombre d'instances de formulaire en fonction du nombre de SA

        for ($i = 0; $i < $nbForms; $i++){
            // Creation de l'instance
            $form = $this->createForm(changerSalleForm::class);
            $form->handleRequest($request);

            // Gestion du formulaire
            if ($form->isSubmitted() && $form->isValid()) {

                // Creation d'une instance de SA dont l'id correspond a celui remonté par le formulaire
                $curSa = $saRepository->find($form->get('sa_id')->getData());

                // Gestion du changement de salle
                $curSa->setState("A_INSTALLER");
                $curSa->setOldRoom($curSa->getCurrentRoom());
                $curSa->setCurrentRoom($form->get('newRoom')->getData());

                // Creation de l'intervention correspondante
                $interventionInstallation = new Intervention();
                $interventionInstallation->setType_I("INSTALLATION");
                $interventionInstallation->setState("EN_COURS");
                $interventionInstallation->setStartingDate(new \DateTime());
                $interventionInstallation->setMessage("Changement de salle");
                $interventionInstallation->setSa($curSa);

                // Envoie des entitées à la base
                $entityManager->persist($interventionInstallation);
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
        ]);
    }


    /* --------------------------------------------------------- */
    /*                   PAGE DE DETAIL DU SA                    */
    /* --------------------------------------------------------- */
    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {

        $entityManager = $doctrine->getManager(); // Manager doctrine

        // Reccuperation du SA avec l'id de la route
        $sa = $entityManager->find(SA::class,$id);

        // Creation d'un intsance de formulaire pour les demandes de maintenances
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
                $sa->setState("A_INSTALLER");
                $installationSA = new Intervention();
                $installationSA->setSa($sa);
                $installationSA->setType_I("INSTALLATION");
                $installationSA->setStartingDate(date_create(date("m.d.y")));
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
        $sa->setState("INACTIF");

        // Creation de l'intervention correspondante
        $InterventionRepo = $doctrine->getRepository("App\Entity\Intervention");
        $intervention = $InterventionRepo->findOneBySA($sa);
        if($intervention == null)
        {
            $intervention = new Intervention();
            $intervention->setSa($sa);
        }
        $intervention->setState("EN_COURS");
        $intervention->setStartingDate(new \DateTime());
        $intervention->setType_I("INSTALLATION");
        $intervention->setMessage("Retour du SA au stock");

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

}


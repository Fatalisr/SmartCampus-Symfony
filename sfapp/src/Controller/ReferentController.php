<?php

namespace App\Controller;
use App\Entity\Intervention;
use App\Entity\SA;
use App\Form\InterventionFormType;
use App\Form\MaintenanceForm;
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
    #[Route('/referent', name: 'app_referent')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $saRepository = $entityManager->getRepository('App\Entity\SA');
        $roomRepository = $entityManager->getRepository('App\Entity\Room');

        $actif = $saRepository->findAllActif();
        $maintenance = $saRepository->findAllMaintenance();
        $installer = $saRepository->findAllInstaller();
        $inactive = $saRepository->findAllInactive();
        $rooms = $roomRepository->findAll();
        $forms = []; //Stockage des instances de formulaire

        $nbForms = sizeof($inactive) + sizeof($actif) + sizeof($installer);

        for ($i = 0; $i < $nbForms; $i++){
            $form = $this->createForm(changerSalleForm::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $curSa = $saRepository->find($form->get('sa_id')->getData());
                if($curSa->getState() != "A_INSTALLER") {
                    $curSa->setState("A_INSTALLER");
                    $interventionInstallation = new Intervention();
                    $interventionInstallation->setType_I("INSTALLATION");
                    $interventionInstallation->setState("EN_COURS");
                    $interventionInstallation->setStartingDate(new \DateTime());
                    $interventionInstallation->setMessage("Changement de salle");
                    $interventionInstallation->setSa($curSa);

                    $curSa->setOldRoom($curSa->getCurrentRoom());

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

    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $nom = $sa->getName();
        $salle = $sa->getCurrentRoom()->getName();
        $etat = $sa->getState();

        $form = $this->createForm(InterventionFormType::class);
        $form->handleRequest($request);
        $report = "";
        if ($form->isSubmitted() && $form->isValid()) {

            $interventionRepo = $doctrine->getRepository("App\Entity\Intervention");
            $curMaintenance = $interventionRepo->findOneBySAReport([$sa]);
            if($curMaintenance != null){
                $report = $curMaintenance->getReport();
            }
            $newMaintenance = new Intervention();
            $newMaintenance->setMessage($form->get('message')->getData());
            date_default_timezone_set('UTC');
            $newMaintenance->setStartingDate(date_create(date("m.d.y")));
            $newMaintenance->setSa($sa);
            $newMaintenance->setType_I("MAINTENANCE");
            $newMaintenance->setState("EN_COURS");
            $sa->setState("MAINTENANCE");
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newMaintenance);
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent', [
            ]);
        }
        return $this->render("referent/sa.html.twig",[
            'nom' => $nom,
            'salle' => $salle,
            'etat' => $etat,
            'form' => $form,
            'report' => $report,
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
    #[Route('/referent/delete_SA/{id}', name: 'delete_sa')]
    public function delete_sa(?int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class, $id);
        $sa->setState("INACTIF");

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

    #[Route('/referent/plan', name: 'plan_salles')]
    public function plan_salles(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $roomRepository = $entityManager->getRepository('App\Entity\Room');
        $roomRDC = $roomRepository->getRoomRDC();
        $roomFloor1 = $roomRepository->getRoomFloor(1);
        $roomFloor2 = $roomRepository->getRoomFloor(2);
        $roomFloor3 = $roomRepository->getRoomFloor(3);

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

    #[Route('/referent/delete_SA_base/{id}', name: 'delete_sa_base')]
    public function delete_sa_base(?int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $interventionRepo = $entityManager->getRepository("App\Entity\Intervention");
        $sa = $entityManager->find(SA::class, $id);
        $intervention = $interventionRepo->findOneBySA($sa);
        if ($intervention != null)
        {
            $entityManager->remove($intervention);
            $entityManager->flush();
        }
        $entityManager->remove($sa);
        $entityManager->flush();
        return $this->redirectToRoute('app_referent', [
        ]);
    }

    #[Route('/referent/historique', name: 'historique')]
    public function historique(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $interventionRepo = $entityManager->getRepository(Intervention::class);
        $interventions = $interventionRepo->findAll();

        return $this->render("referent/historique.html.twig", [
            'interventions' => $interventions,
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
                $sa->setState('ACTIF');
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


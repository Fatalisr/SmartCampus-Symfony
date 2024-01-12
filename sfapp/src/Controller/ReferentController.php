<?php

namespace App\Controller;
use App\Entity\Intervention;
use App\Entity\SA;
use App\Form\InterventionFormType;
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

        $nbForms = sizeof($inactive) + sizeof($actif);

        for ($i = 0; $i < $nbForms; $i++){
            $form = $this->createForm(changerSalleForm::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $curSa = $saRepository->find($form->get('sa_id')->getData());
                $curSa->setState("A_INSTALLER");


                $curSa->setOldRoom($curSa->getCurrentRoom());
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
        ]);
    }

    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine,Request $request,  ConnexionRequetesAPI $requetesAPI): Response
    {
        date_default_timezone_set('Europe/Paris');


        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $room = $sa->getCurrentRoom()->getName();

        $today = date("Y-m-d H:i:s"); //Genère la date d'ajourd'hui
        $yesterday = date('Y-m-d H:i:s', time() + (60 * 60 * 24)*-1 );  // Genère la date d'hier
        $lastWeek = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * -7) ); // Genère la date de la semain dernière
        $lastHour = date('Y-m-d H:i:s', time() + (60 * 60 * 1 * -1) ); // Genère la date h-1
        $reponseT = $requetesAPI->getIntervalCaptures($yesterday,$today,$room); // Récupère les captures d'hier à aujourd'hui
        $reponseLW = $requetesAPI->getIntervalCaptures($lastWeek,$today,$room); // Récupère les captures depuis la semaine dernière
        $reponseH = $requetesAPI->getIntervalCaptures($lastHour,$today,$room); // Récupère les captures de la dernière heure

        $form = $this->createForm(InterventionFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $maintenance = new Intervention();
            $maintenance->setMessage($form->get('message')->getData());
            $maintenance->setStartingDate($today);
            $maintenance->setSa($sa);
            $maintenance->setType("MAINTENANCE");
            $sa->setState("MAINTENANCE");
            $entityManager = $doctrine->getManager();
            $entityManager->persist($maintenance);
            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent', [
            ]);
        }
        //var_dump($lastHour);
        return $this->render("referent/sa.html.twig",[
            'sa' => $sa,
            'form' => $form,
            'donneesT' => $reponseT,
            'donneesH' => $reponseH,
            'donneesLW' => $reponseLW,
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
                $installationSA->setType("INSTALLATION");
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

        $intervention = new Intervention();
        $intervention->setSa($sa);
        //$intervention->setStartingDate();
        $intervention->setType("INSTALLATION");
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

        $sa = $entityManager->find(SA::class, $id);
        $entityManager->remove($sa);
        $entityManager->flush();
        return $this->redirectToRoute('app_referent', [
        ]);
    }
}


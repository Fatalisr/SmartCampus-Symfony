<?php

namespace App\Controller;
use App\Entity\SA;
use App\Entity\Room;
use App\Repository\RoomRepository;
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
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $saRepository = $entityManager->getRepository('App\Entity\SA');
        $roomRepository = $entityManager->getRepository('App\Entity\Room');

        $planAction = $saRepository->findAllPlanAction();
        $inactive = $saRepository->findAllInactive();
        $rooms = $roomRepository->findAll();

        return $this->render("referent/referent.html.twig", [
        'path' => 'src/Controller/ReferentController.php',
        'planAction' => $planAction,
        'inactive' => $inactive,
        'rooms' => $rooms,
        ]);
    }

    #[Route('/referent/sa/{id}', name: 'app_view_sa')]
    public function view_sa(?int $id,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $nom = $sa->getName();
        $salle = $sa->getCurrentRoom()->getName();
        $etat = $sa->getState();

        return $this->render("referent/sa.html.twig",[
            'nom' => $nom,
            'salle' => $salle,
            'etat' => $etat,
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

    #[Route('/referent/changersalle/{id}', name: 'changer_salle_sa')]
    public function changeRoom(?int $id,Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class,$id);
        $nom = $sa->getName();

        $changeRoom = $this->createForm(changerSalleForm::class);
        $changeRoom->handleRequest($request);

        if ($changeRoom->isSubmitted() && $changeRoom->isValid()) {

            if($changeRoom->get('newRoom')->getData())
            {
                $sa->setState("A_INSTALLER");
            }
            else
            {
                $sa->setState("INACTIF");
            }
            $sa->setOldRoom($sa->getCurrentRoom());
            $sa->setCurrentRoom($changeRoom->get('newRoom')->getData());

            $entityManager->persist($sa);
            $entityManager->flush();

            return $this->redirectToRoute('app_referent', [
            ]);
        }

        return $this->render("referent/changersalle.html.twig",[
            'changeRoom' => $changeRoom,
        ]);
    }
    #[Route('/referent/delete_SA/{id}', name: 'delete_sa')]
    public function delete_sa(?int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sa = $entityManager->find(SA::class, $id);

        $sa->setOldRoom($sa->getCurrentRoom());
        $sa->setCurrentRoom(null);
        $sa->setState("INACTIF");
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
}

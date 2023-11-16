<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    #[Route('/', name: 'login')]
    public function login(Request $request, ManagerRegistry $doctrine): Response
    {
        // Créer le formulaire de connexion
        $form = $this->createForm(LoginForm::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $login = $form->get('username')->getData();

            $entityManager = $doctrine->getManager();

            $user = $entityManager->getRepository(Member::class)->findOneBy(['login' => $login]);

            if($user){
                if($user->getPassword() == $form->get('password')->getData() && $user->getRole() == "REFERENT"){
                    return $this->redirectToRoute('app_referent');
                }elseif ($user->getPassword() == $form->get('password')->getData() && $user->getRole() == "TECHNICIEN"){
                    return $this->redirectToRoute('app_technicien');
                }else{

                    return $this->render('/index/index.html.twig', [
                        'form' => $form->createView(),
                        'error' => 'badPwd'
                    ]);
                }
            }else{
                return $this->render('/index/index.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'badUser'
                ]);
            }
        }

        return $this->render('/index/index.html.twig', [
            'form' => $form->createView(),
            'error' => null,
        ]);
    }
}

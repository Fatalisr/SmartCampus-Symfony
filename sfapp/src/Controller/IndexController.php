<?php

namespace App\Controller;
use App\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/*
 * @brief Controller that handles the login page and the connection
 */
class IndexController extends AbstractController
{
    /*
     * Route gérant la page de connexion et la connexion à l'application
     */
    #[Route('/', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Création du formulaire de connexion
        $form = $this->createForm(LoginForm::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        $error = null;

        // Récupérer l'erreur précedente s'il y en a eu une
        $erreurServer = $authenticationUtils->getLastAuthenticationError();
        if($erreurServer)
        {
            // Envoi d'un message d'erreur personnalisé
            if ($erreurServer->getMessage() ==  "Bad credentials.") {
                $error = 'badUser';
            } elseif ($erreurServer->getMessage() == "The presented password is invalid.") {
                $error = 'badPwd';
            }
        }
        // Renvoie vers la page de connexion
        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}

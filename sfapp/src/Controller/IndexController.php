<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\LoginForm;
use App\Service\ConnexionRequetesAPI;
use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class IndexController extends AbstractController
{


    #[Route('/', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Créer le formulaire de connexion
        $form = $this->createForm(LoginForm::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        $error = null;

        //$date1 = new \DateTime('2023-12-20');
        //$date2 = new \DateTime('2023-12-21');
        //$api = $service->getIntervalCaptures('2023-12-20','2023-12-21');
        //$api = $service->getCaptures();

        // get the login error if there is one
        $erreurServer = $authenticationUtils->getLastAuthenticationError();;
        if($erreurServer)
        {
            // Customizing error message based on the error type
            if ($erreurServer->getMessage() ==  "Bad credentials.") {
                $error = 'badUser'; // You can customize this message
            } elseif ($erreurServer->getMessage() == "The presented password is invalid.") {
                $error = 'badPwd'; // You can customize this message
            }
        }
        //dump($error);
        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}

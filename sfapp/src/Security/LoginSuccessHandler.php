<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{


    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?\Symfony\Component\HttpFoundation\Response
    {
        $user = $token->getUser();

        if(in_array("ROLE_REFERENT", $user->getRoles()))
        {
            return new RedirectResponse('/referent');
        }
        else if(in_array("ROLE_TECHNICIEN", $user->getRoles()))
        {
            return new RedirectResponse('/technicien');
        }
        else
        {
            return new RedirectResponse('/');        }
    }


}
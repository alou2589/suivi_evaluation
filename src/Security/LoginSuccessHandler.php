<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router=$router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user= $token->getUser();
        $roles=$user->getRoles();

        //if(in_array('ROLE_USER', $roles)){
        //    return new RedirectResponse($this->router->generate('app_user_home_index'));
        //}
        if(in_array('ROLE_SUPERADMIN', $roles)){
            return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
        }
        if(in_array('ROLE_RH_ADMIN', $roles)){
            return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
        }
        if(in_array('ROLE_INFO_ADMIN', $roles)){
            return new RedirectResponse($this->router->generate('app_admin_dashboard_informatique'));
        }
        if(in_array('ROLE_CEPSE_ADMIN', $roles)){
            return new RedirectResponse($this->router->generate('app_admin_dashboard_cepse'));
        }

        return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
    }
}

<?php

namespace App\Security;

use App\Entity\Visite;
use Doctrine\ORM\EntityManagerInterface;
use donatj\UserAgent\UserAgentParser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em,RouterInterface $router)
    {
        $this->em=$em;
        $this->router=$router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user= $token->getUser();
        $roles=$user->getRoles();
        $parser=new UserAgentParser();
        $userAgent=$request->headers->get('User-Agent');
        $navigateur=$parser->parse($userAgent);
        $visite=new Visite();
        $visite->setUser($user);
        $visite->setAction('Connexion');
        $visite->setDateAction(new \DateTime());
        $visite->setPageVisitee('Page accueil');
        $visite->setIpVisiteur($request->getClientIp());
        $visite->setNavigateur($navigateur->browser());


        //if(in_array('ROLE_USER', $roles)){
        //    return new RedirectResponse($this->router->generate('app_user_home_index'));
        //}
        if(in_array('ROLE_SUPERADMIN', $roles)){

            $this->em->persist($visite);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
        }
        if(in_array('ROLE_RH_ADMIN', $roles)){

            $this->em->persist($visite);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
        }
        if(in_array('ROLE_INFO_ADMIN', $roles)){

            $this->em->persist($visite);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('app_admin_dashboard_informatique'));
        }
        if(in_array('ROLE_CEPSE_ADMIN', $roles)){

            $this->em->persist($visite);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('app_admin_dashboard_cepse'));
        }

        return new RedirectResponse($this->router->generate('app_admin_dashboard_ressources_humaines'));
    }
}

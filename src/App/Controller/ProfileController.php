<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\User;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function profileAction() : Response
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(User::authCookieName);

        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);

        if ($userToken->isAnonymous())
        {
            return $this->response;
        }

        $userInfo = $this->container->get(UserInfoRepository::class)->getInfo($userToken->getUser()->getId());
        $this->render('profile.html.twig', $data);
        return $this->response;
    }
}
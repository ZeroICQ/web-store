<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\User;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function viewProfileAction() : Response
    {
        $data = [];
        $userId = $this->request->query->getDigits('id', 0);

        if ($userId) {
            $data['userInfo']  = $this->container->get(UserInfoRepository::class)->getInfo($userId);
        }

        $this->render('viewProfile.html.twig', $data);
        return $this->response;
    }

    public function editProfileAction()
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(User::authCookieName);

        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);

//        if ($this->isPost()) {
//
//        }

        //TODO:continue

        $data['userToken'] = $userToken;

        if ($userToken->isAnonymous())
        {
            return $this->response;
        }

        $userInfo = $this->container->get(UserInfoRepository::class)->getInfo($userToken->getUser()->getId());
        $this->render('viewProfile.html.twig', $data);

    }
}
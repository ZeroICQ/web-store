<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
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
        //
//        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);
//
//        if ($userToken->isAnonymous())
//        {
//            return $this->response;
//        }
//
//        $userInfo = $this->container->get(UserInfoRepository::class)->getInfo($userToken->getUser()->getId());
//        $this->render('viewProfile.html.twig', $data);

    }
}
<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\User;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function viewProfileAction() : Response
    {
        $data = [];
        $userId = $this->request->query->getDigits('id', null);

        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);
        $data['userToken'] = $userToken;

        if ($userId) {
            $data['userInfo']  = $this->container->get(UserInfoRepository::class)->getInfo($userId);
        }

        $this->render('view_profile.html.twig', $data);
        return $this->response;
    }

    public function editProfileAction()
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);
        $data['userToken'] = $userToken;

        if ($userToken->isAnonymous())
        {
            return $this->response;
        }

        if ($this->isPost()) {
            $firstName  = $this->request->request->filter('firstName');
            $secondName = $this->request->request->filter('secondName');
            $workPlace  = $this->request->request->filter('workPlace');
            $biography  = $this->request->request->filter('biography');

            $this->container->get(UserInfoRepository::class)->updateInfo(
                $userToken->getUser()->getId(),
                $firstName,
                $secondName,
                $workPlace,
                $biography
            );
        }

        $data['userInfo'] = $this->container->get(UserInfoRepository::class)
                                ->getInfo($userToken->getUser()->getId());

        $this->render('edit_profile.html.twig', $data);
        return $this->response;
    }
}
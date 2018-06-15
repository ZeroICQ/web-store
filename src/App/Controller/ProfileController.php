<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\Repository\UserInfoRepositoryInterface;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\Service\AuthenticationServiceInterface;
use App\Authentication\User;
use App\Authentication\UserInfoInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class ProfileController extends BaseController
{
    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;
    /**
     * @var UserInfoRepositoryInterface
     */
    private $userInfoRepository;

    /**
     * ProfileController constructor.
     * @param Request $request
     * @param Twig_Environment $twig
     * @param AuthenticationServiceInterface $authenticationService
     * @param UserInfoRepositoryInterface $userInfoRepository
     */
    public function __construct(Request $request, Twig_Environment $twig,
                                AuthenticationServiceInterface $authenticationService,
                                UserInfoRepositoryInterface $userInfoRepository)
    {
        parent::__construct($request, $twig);

        $this->authenticationService = $authenticationService;
        $this->userInfoRepository = $userInfoRepository;
    }

    public function viewProfileAction() : Response
    {
        $data = [];
        $userId = $this->request->query->getDigits('id', null);

        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->authenticationService->authenticate($authCookieValue);
        $data['userToken'] = $userToken;

        if ($userId) {
            $data['userInfo']  = $this->userInfoRepository->getInfo($userId);
        }

        $this->render('view_profile.html.twig', $data);
        return $this->response;
    }

    public function editProfileAction()
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->authenticationService->authenticate($authCookieValue);
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

            $updateResult = $this->userInfoRepository->updateInfo(
                            $userToken->getUser()->getId(),
                            $firstName,
                            $secondName,
                            $workPlace,
                            $biography);
            if ($updateResult) {
                $this->response = new RedirectResponse('/profile?id='.$userToken->getUser()->getId());
            }
        }

        $data['userInfo'] = $this->userInfoRepository->getInfo($userToken->getUser()->getId());

        $this->render('edit_profile.html.twig', $data);
        return $this->response;
    }
}

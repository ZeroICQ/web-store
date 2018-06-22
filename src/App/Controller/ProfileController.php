<?php


namespace App\Controller;

use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\Repository\UserInfoRepositoryInterface;
use App\Authentication\Repository\UserRepository;
use App\Authentication\Repository\UserRepositoryInterface;
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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * ProfileController constructor.
     * @param Request $request
     * @param Twig_Environment $twig
     * @param AuthenticationServiceInterface $authenticationService
     * @param UserRepositoryInterface $userRepository
     * @param UserInfoRepositoryInterface $userInfoRepository
     */
    public function __construct(Request $request, Twig_Environment $twig,
                                AuthenticationServiceInterface $authenticationService,
                                UserRepositoryInterface $userRepository,
                                UserInfoRepositoryInterface $userInfoRepository)
    {
        parent::__construct($request, $twig);

        $this->authenticationService = $authenticationService;
        $this->userInfoRepository    = $userInfoRepository;
        $this->userRepository        = $userRepository;
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
            $data['user']      = $userToken->getUser();
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
            $login      = $this->request->request->filter('login');
            $firstName  = $this->request->request->filter('firstName');
            $secondName = $this->request->request->filter('secondName');
            $workPlace  = $this->request->request->filter('workPlace');
            $biography  = $this->request->request->filter('biography');

            $updateResult = $this->userRepository->update(
                            $userToken->getUser()->getId(),
                            $login,
                            $firstName,
                            $secondName,
                            $workPlace,
                            $biography);
            if ($updateResult) {
                $this->response = new RedirectResponse('/profile?id='.$userToken->getUser()->getId());
                $authCookieValue = $this->authenticationService->regenerateCredentials($login);
                //TODO: move common function code
                $authCookie = LoginController::get30DaysCookie(User::authCookieName, $authCookieValue);
                $this->response->headers->setCookie($authCookie);
                return $this->response;
            }
        }

        $data['userInfo'] = $this->userInfoRepository->getInfo($userToken->getUser()->getId());
        $data['user'] = $userToken->getUser();

        $this->render('edit_profile.html.twig', $data);
        return $this->response;
    }
}

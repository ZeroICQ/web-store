<?php


namespace App\Api;


use App\Authentication\Repository\UserRepositoryInterface;
use App\Authentication\Service\AuthenticationServiceInterface;
use App\Authentication\User;
use App\Controller\LoginController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserInfoApiController extends BaseApiController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;

    /**
     * UserInfoApiController constructor.
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(Request $request, UserRepositoryInterface $userRepository,
                                AuthenticationServiceInterface $authenticationService)
    {
        parent::__construct($request);
        $this->userRepository = $userRepository;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return Response
     */
    public function getInfoAction(): Response
    {
        $userId = $this->request->query->getDigits('id', null);
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            $data = ['error' => 'no such user'];
            $this->response->setStatusCode(404);
        } else {
            $data = $user->toArray();
        }
        $this->render($data);
        return $this->response;
    }

    /**
     * @return Response
     */
    public function updateUser(): Response
    {
        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->authenticationService->authenticate($authCookieValue);

        if ($userToken->isAnonymous()) {
            $this->response = new Response("", 401);
            return $this->response;
        }

        if ($this->request->isMethod('POST')) {
            $login      = $this->request->request->filter('login');
            $firstName  = $this->request->request->filter('firstName');
            $secondName = $this->request->request->filter('secondName');
            $workPlace  = $this->request->request->filter('workplace');
            $biography  = $this->request->request->filter('bio');

            $updateResult = $this->userRepository->update(
                $userToken->getUser()->getId(),
                $login,
                $firstName,
                $secondName,
                $workPlace,
                $biography
            );

            if ($updateResult) {
                $authCookieValue = $this->authenticationService->regenerateCredentials($login);
                //TODO: move common function code
                $authCookie = LoginController::get30DaysCookie(User::authCookieName, $authCookieValue);
                $this->response->headers->setCookie($authCookie);
                $data = $this->userRepository->findById($userToken->getUser()->getId())->toArray();
                $this->response->setContent(json_encode($data));
                return $this->response;
            }
        }


        $this->response->setStatusCode(401);
        return $this->response;
    }
}

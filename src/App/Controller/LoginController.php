<?php

namespace App\Controller;


use App\Authentication\Service\AuthenticationServiceInterface;
use App\Authentication\User;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class LoginController extends BaseController
{
    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;

    /**
     * LoginController constructor.
     * @param Request $request
     * @param Twig_Environment $twig
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(Request $request, Twig_Environment $twig, AuthenticationServiceInterface $authenticationService)
    {
        parent::__construct($request, $twig);

        $this->authenticationService = $authenticationService;
    }

    /**
     * @return Response
     */
    public function signInAction() : Response
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(User::authCookieName);

        if ($this->isPost()) {

            $rawLogin = $this->request->request->getAlnum('login');
            $rawPassword  = $this->request->request->getAlnum('password');

            if (strlen($rawLogin) < 1 || strlen($rawPassword) < 1) {
                $data['noPassword'] = strlen($rawLogin)    < 1 ;
                $data['noLogin']    = strlen($rawPassword) < 1 ;

                $this->render('sign_in.html.twig', $data);
                return $this->response;
            }

            $authCookieValue = $this->authenticationService->generateCredentials($rawLogin, $rawPassword);

            $authCookie = $this->get30DaysCookie(User::authCookieName, $authCookieValue);
            $this->response->headers->setCookie($authCookie);
        }

        $userToken = $this->authenticationService->authenticate($authCookieValue);

        $data['userToken'] = $userToken;
        $this->render('sign_in.html.twig', $data);

        return $this->response;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function registerAction(): Response
    {
        $authCookieValue = $this->request->cookies->get(User::authCookieName);
        $userToken = $this->authenticationService->authenticate($authCookieValue);
        $data['userToken'] = $userToken;

        if (!$this->isPost()) {
            $this->render('register.html.twig', $data);
            return $this->response;
        }

        $login = $this->request->request->getAlnum('login');
        $rawPassword = $this->request->request->getAlnum('password');

        $userToken = $this->authenticationService->registerUser($login, $rawPassword);
        $authCookieValue = $this->authenticationService->generateCredentials($login, $rawPassword);

        $authCookie = $this->get30DaysCookie(User::authCookieName, $authCookieValue);
        $this->response->headers->setCookie($authCookie);

        if (!$userToken->isAnonymous()) {
            $this->response = new RedirectResponse('/');
            $this->response->headers->setCookie($authCookie);
        } else {
            $this->render('register.html.twig');
        }

        return $this->response;
    }

    /**
     * @return Response
     */

    public function logoutAction() : Response
    {
        $this->response = new RedirectResponse('/');
        $this->response->headers->clearCookie(User::authCookieName);
        return $this->response;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Cookie
     */
    private function get30DaysCookie(string $name, string $value) : Cookie
    {
        $now = new DateTime();

        try {
            $authInterval = new DateInterval('P30D');
        } catch (\Exception $e) {
            $authInterval = 0;
        }

        return new Cookie($name, $value, $now->add($authInterval));
    }
}

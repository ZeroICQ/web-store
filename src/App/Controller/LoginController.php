<?php

namespace App\Controller;


use App\Authentication\Service\AuthenticationService;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends BaseController
{
    /*
     * @var string
     * ASK: where to put?
     */
    private const authCookieName = 'auth_cookie';

    /**
     * @return Response
     * @throws \Exception
     */
    public function signInAction() : Response
    {
        $data = [];
        $authCookieValue = $this->request->cookies->get(self::authCookieName);

        if ($this->isPost()) {

            $rawLogin = $this->request->request->getAlnum('login');
            $rawPassword  = $this->request->request->getAlnum('password');

            if (strlen($rawLogin) < 1 || strlen($rawPassword) < 1) {
                $data['noPassword'] = strlen($rawLogin)    < 1 ;
                $data['noLogin']    = strlen($rawPassword) < 1 ;

                $this->render('sign_in.html.twig', $data);
                return $this->response;
            }

            $authCookieValue = $this->container->get(AuthenticationService::class)
                ->generateCredentials($rawLogin, $rawPassword);

            $authCookie = $this->get30DaysCookie(self::authCookieName, $authCookieValue);
            $this->response->headers->setCookie($authCookie);
        }

        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);

        if (!$userToken->isAnonymous()) {
            $data['login'] = $userToken->getUser()->getLogin();
        }

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
        $authCookieValue = $this->request->cookies->get(self::authCookieName);
        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);
        $data['userToken'] = $userToken;

        if (!$this->isPost()) {
            $this->render('register.html.twig', $data);
            return $this->response;
        }

        $login = $this->request->request->getAlnum('login');
        $rawPassword = $this->request->request->getAlnum('password');

        $userToken = $this->container->get(AuthenticationService::class)->registerUser($login, $rawPassword);
        $authCookieValue = $this->container->get(AuthenticationService::class)
            ->generateCredentials($login, $rawPassword);

        $authCookie = $this->get30DaysCookie(self::authCookieName, $authCookieValue);
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
        $this->response->headers->clearCookie(self::authCookieName);
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
            //really?
            $authInterval = 0;
        }

        return new Cookie($name, $value, $now->add($authInterval));
    }
}

<?php

namespace App\Controller;


use App\Authentication\Encoder\UserPasswordEncoder;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\User;
use App\Controller\BaseController;
use DateInterval;
use DateTime;
use function Sodium\add;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends BaseController
{
    /*
     * @var string
     */
    private const authCookieName = 'auth_cookie';//ASK: where to put?

    /**
     * @return string
     */
    public function signInAction() : Response
    {

        $data = [];
        $authCookieValue = $this->request->cookies->get(self::authCookieName);

        if ($this->isPost()) {

            $login = $this->request->request->getAlnum('login');
            $pass = $this->request->request->getAlnum('password');

            $user = new User(null, $login, $pass);

            $authCookieValue = $this->container->get(AuthenticationService::class)->generateCredentials($user);

            //set cookie
            $now = new DateTime();
            $authInterval = new DateInterval('P30D');//30days
            $authCookie = new Cookie(self::authCookieName, $authCookieValue, $now->add($authInterval));

            $this->response->headers->setCookie($authCookie);
        }

        $userToken = $this->container->get(AuthenticationService::class)->authenticate($authCookieValue);

//
        if (!$userToken->isAnonymous()) {
            $data['login'] = $userToken->getUser()->getLogin();
        }

        $this->render('signIn.html.twig', $data);

        return $this->response;
    }

    /**
     * @return Response
     */
    public function registerAction(): Response
    {
//        $data = [];
//
//        if ($this->isGet()) {
//            $this->render('register.html.twig');
//        } elseif($this->isPost()) {
//
//            $login = $this->request->request->getAlnum('login');
//            $rawPass = $this->request->request->getAlnum('password');
//            $cryptPass = UserPasswordEncoder::encodePassword($rawPass);
//
//            $user = new User(null, $login, $cryptPass);
//            $sql_errors = $this->container->get('entityManager')->getRepository('user')->save($user);
//
//            //successfully registered
//            if (count($sql_errors) === 0) {
//                $this->container->get('auth')->generateCredentials($user, $this->getSession());
//                $this->response = new RedirectResponse('/');
//                return $this->response;
//
//            }
//            $data['sql_errors']  = $sql_errors;
//            $this->render('register.html.twig', $data);
//        }

        return $this->response;
    }

    public function logoutAction() : Response
    {
//        $this->getSession()->invalidate();
//        $this->response = new RedirectResponse('/');
        return $this->response;
    }
}

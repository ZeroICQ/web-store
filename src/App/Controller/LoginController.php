<?php

namespace App\Controller;


use App\Authentication\Encoder\UserPasswordEncoder;
use App\Authentication\User;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends BaseController
{
    /**
     * @return string
     */
    public function signInAction() : Response
    {
        $data = [];
        $session = $this->container->get('session');

        if ($this->isPost()) {

            $login = $this->request->request->getAlnum('login');
            $pass = $this->request->request->getAlnum('password');

            $user = $this->container->get('entityManager')->getRepository('user')->findByLoginPassword($login, $pass);

            $this->container->get('auth')->generateCredentials($user, $session);
        }

        $userToken = $this->container->get('auth')->authenticate($session);
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
        $data = [];

        if ($this->isGet()) {
            $this->render('register.html.twig');
        } elseif($this->isPost()) {

            $login = $this->request->request->getAlnum('login');
            $rawPass = $this->request->request->getAlnum('password');
            $cryptPass = UserPasswordEncoder::encodePassword($rawPass);

            $user = new User(null, $login, $cryptPass);
            $sql_errors = $this->container->get('entityManager')->getRepository('user')->save($user);

            //successfully registered
            if (count($sql_errors) === 0) {
                $this->container->get('auth')->generateCredentials($user, $this->getSession());
                $this->response = new RedirectResponse('/');
                return $this->response;

            }
            $data['sql_errors']  = $sql_errors;
            $this->render('register.html.twig', $data);
        }

        return $this->response;
    }

    public function logoutAction() : Response
    {
        $this->getSession()->invalidate();
        $this->response = new RedirectResponse('/');
        return $this->response;
    }
}

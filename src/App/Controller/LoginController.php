<?php

namespace App\Controller;


use App\Authentication\Encoder\UserPasswordEncoder;
use App\Authentication\User;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends BaseController
{
    /**
     * @return string
     */
    public function signInAction() : Response
    {
//        if ($this->isPost()) {
//            $login = $this->request->request->getAlnum('login');
//            $pass = $this->request->request->getAlnum('password');
//        }
//        $userToken = $this->container->get('auth')->authenticate('test');
//
//        if (!$userToken->isAnonymous()) {
//            $name = $userToken->getUser()->getLogin();
//        }

        $this->render('signIn.html.twig');

        return $this->response;
    }

    public function registerAction()
    {
        $data = [];

        if ($this->isGet()) {
            $this->render('register.html.twig');
        } elseif($this->isPost()) {

            $login = $this->request->request->getAlnum('login');
            $rawPass = $this->request->request->getAlnum('password');
            $cryptPass = UserPasswordEncoder::encodePassword($rawPass);

            $user = new User(null, $login, $cryptPass);
            $data['sql_errors'] = $this->container->get('entityManager')->getRepository('user')->save($user);

            $this->render('register.html.twig', $data);
        }

        return $this->response;
    }
}

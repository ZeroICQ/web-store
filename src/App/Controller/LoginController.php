<?php

namespace App\Controller;


use App\Controller\BaseController;

class LoginController extends BaseController
{
    /**
     * @return string
     */
    public function signInAction()
    {

        $userToken = $this->container->get('auth')->authenticate('test');

        if (!$userToken->isAnonymous()) {
            $name = $userToken->getUser()->getLogin();
        }

        return $this->container->get('twig')->render('signIn.html.twig', ['name'=>$name]);
    }
}
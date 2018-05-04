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
        $user = $this->container->get('auth')->authenticate('myuser');
        $name = $user->getUser()->getLogin();
        return $this->container->get('twig')->render('index.html', ['name'=>$name]);
    }
}
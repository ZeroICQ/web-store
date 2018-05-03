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
        return $this->twig->render('index.html');
    }
}
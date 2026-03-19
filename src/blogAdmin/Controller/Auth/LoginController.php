<?php
namespace App\blogAdmin\Controller\Auth;

use App\blogAdmin\Controller\AuthController;

class LoginController extends AuthController
{

    public function renderLoginForm()
    {

        $this->render('auth/login', []);

    }

}

<?php
namespace BlogApp\Admin\Controller\AuthController;

use BlogApp\Admin\Controller\AuthPagesController;

class LoginController extends AuthPagesController
{

    public function renderLoginForm()
    {

        $this->render('auth/login', []);

    }

}

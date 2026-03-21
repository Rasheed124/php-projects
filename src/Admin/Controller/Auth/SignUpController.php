<?php
namespace App\blogAdmin\Controller\Auth;

use App\blogAdmin\Controller\AuthController;

class SignUpController extends AuthController
{

    public function renderSignUpForm()
    {

        $this->render('auth/signup', []);

    }

}

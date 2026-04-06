<?php
namespace App\Admin\Controller\Auth;

use App\Admin\Controller\AbstractAdminController;

class LoginController extends AbstractAdminController
{

    public function login()
    {

        // Render login form with any errors
        $this->render('auth/login', [
        ]);
    }
}

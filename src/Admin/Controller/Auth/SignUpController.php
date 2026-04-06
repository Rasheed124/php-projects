<?php
namespace App\Admin\Controller\Auth;

use App\Admin\Controller\AbstractAdminController;



class SignUpController extends AbstractAdminController
{

    public function signup()
    {

        $this->render('auth/signup', []);
    }

}

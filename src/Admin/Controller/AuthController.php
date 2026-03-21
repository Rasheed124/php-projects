<?php
namespace App\blogAdmin\Controller;

use App\blogAdmin\Controller\Auth\LoginController;
use App\blogAdmin\Controller\Auth\SignUpController;

class AuthController extends AbstractAdminController
{
    public function renderAuthScreens($page)
    {
        
        switch ($page) {
            case 'login':
                $loginController = new LoginController();
                return $loginController->renderLoginForm();
                break;
                
            case 'signup':
                $signUpController = new SignUpController();
                return $signUpController->renderSignUpForm();
                break;
                
            default:
               header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                exit();
        }
    }
}
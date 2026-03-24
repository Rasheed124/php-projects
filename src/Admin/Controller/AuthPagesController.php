<?php
namespace BlogApp\Admin\Controller;

use BlogApp\Admin\Controller\AuthController\LoginController;
use BlogApp\Admin\Controller\AuthController\SignUpController;
use BlogApp\Repository\Auth\AuthPagesRepository;


class AuthPagesController extends AbstractAdminController
{
    public function __construct(protected AuthPagesRepository $authPagesRepository, SessionController $sessionController)
    {
         parent::__construct($sessionController);
    }

    public function renderAuthScreens($page)
    {

   
        switch ($page) {

            case 'login':

                $loginController = new LoginController($this->authPagesRepository, $this->sessionController);
                return $loginController->renderLoginForm();
                break;

            case 'signup':

                $signUpController = new SignUpController($this->authPagesRepository, $this->sessionController);
                return $signUpController->renderSignUpForm();
                break;

            case 'logout':
                $this->sessionController->logout();
                header('Location: index.php?' . http_build_query(['route' => 'pages', 'page'=> 'index']));
                break;

            default:
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                exit();
        }
    }
}

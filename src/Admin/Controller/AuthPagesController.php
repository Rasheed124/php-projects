<?php
namespace BlogApp\Admin\Controller;

use BlogApp\Admin\Controller\AuthController\LoginController;
use BlogApp\Admin\Controller\AuthController\SignUpController;
use BlogApp\Admin\Repository\AuthRepository\AuthPagesRepository;

class AuthPagesController extends AbstractAdminController
{
   
    protected AuthPagesRepository $authPagesRepository;

    public function __construct(AuthPagesRepository $authPagesRepository)
    {
        $this->authPagesRepository = $authPagesRepository;
    }

    public function renderAuthScreens($page)
    {
        switch ($page) {

            case 'login':

                $loginController = new LoginController($this->authPagesRepository);
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

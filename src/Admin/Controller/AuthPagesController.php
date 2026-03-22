<?php
namespace BlogApp\Admin\Controller;

use BlogApp\Admin\Controller\AuthController\LoginController;
use BlogApp\Admin\Controller\AuthController\SignUpController;
use BlogApp\Admin\Repository\AuthRepository\AuthPagesRepository;

class AuthPagesController extends AbstractAdminController
{
    protected SessionController $handleIsLoggedOut;
    public function __construct(protected AuthPagesRepository $authPagesRepository)
    {
        $this->handleIsLoggedOut = new SessionController();
    }

    public function renderAuthScreens($page)
    {
        switch ($page) {

            case 'login':

                $loginController = new LoginController($this->authPagesRepository);
                return $loginController->renderLoginForm();
                break;

            case 'signup':

                $signUpController = new SignUpController($this->authPagesRepository);
                return $signUpController->renderSignUpForm();
                break;

            case 'logout':
                 $this->handleIsLoggedOut->logout();
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                break;

            default:
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                exit();
        }
    }
}

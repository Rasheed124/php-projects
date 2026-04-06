<?php
namespace App\Admin\Controller\Auth;

// use App\Admin\Controller\AbstractAdminController;

// use App\Frontend\Controller\PagesController;

// use App\Admin\Support\AdminSupport;

class AuthPagesController
{

    // public function __construct( AdminSupport $sessionController)
    // {
    //     parent::__construct($sessionController);

    // }

    public function renderAuthPages($page)
    {

        // if (empty($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession())) {
        //     header('Location: index.php?' . url('admin', 'auth'));
        //     // header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
        //     return;
        // }

        $loginController  = new LoginController();
        $signupController = new SignUpController();
        switch ($page) {

            case 'login':
                return $loginController->login();
                break;
            case 'signup':
                return $signupController->signup();
                break;
            case 'logout':
                // $this->sessionController->logout();
                header('Location: index.php?' . url('/logout'));
                break;
            default:
                header('Location: index.php?' . url('admin', 'auth'));
                die();
        }

    }

}

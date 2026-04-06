<?php
namespace App\Admin\Controller\Auth;

use App\Admin\Support\AdminSupport;

class AuthPagesController
{

    public function __construct(
        protected LoginController $loginController,
        protected SignupController $signupController,
        protected AdminSupport $sessionController
    ) {
    }

    public function renderAuthPages($page)
    {
        switch ($page) {
            case 'login':
                return $this->loginController->login();

            case 'signup':
                return $this->signupController->signup();

            case 'logout':
                $this->sessionController->logout();
                header('Location: ' . url('admin/auth/login'));
                exit();

            default:
                // If someone types /admin/auth/xyz, send them back to login
                header('Location: ' . url('admin/auth/login'));
                exit();
        }
    }
}

<?php
namespace App\Admin\Controller\Auth;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Auth\AuthPagesRepository;

class LoginController extends AbstractAdminController
{

    public function __construct(AdminSupport $sessionController, protected AuthPagesRepository $authPagesRepository, )
    {
        parent::__construct($sessionController);
    }

    public function login()
    {

        if ($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession()) {
            header('Location: ' . url('admin', 'dashboard'));
            return;
        }

        $loginError = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (! empty($email) && ! empty($password)) {

                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $loginError = "Invalid email format.";
                } else {

                    $user = $this->authPagesRepository->getUserByEmail($email);

                    if ($user && password_verify($password, $user['password'])) {

                        $this->sessionController->setUserSession($user['id'], $user['username'], $user['email']);

                        // header("Location: index.php?route=admin/pages");
                        header("Location:" . url('admin/dashboard'));

                        exit;
                    } else {
                        $loginError = "Invalid email or password.";
                    }
                }
            } else {
                $loginError = "Please fill in both fields.";
            }
        }

        // Render login form with any errors
        $this->render('auth/login', [
            'loginError' => $loginError,
        ]);
    }
}

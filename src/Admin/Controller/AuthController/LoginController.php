<?php
namespace BlogApp\Admin\Controller\AuthController;

use BlogApp\Admin\Controller\AuthPagesController;
use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\Auth\AuthPagesRepository;

class LoginController extends AuthPagesController
{

    public function __construct(AuthPagesRepository $authPagesRepository, SessionController $sessionController)
    {
        parent::__construct($authPagesRepository, $sessionController);
    }

    public function renderLoginForm()
    {

        if ($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession()) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/pages']));
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

                    if ($user && password_verify($password, $user['password_hash'])) {

                        $this->sessionController->setUserSession($user['id'], $user['username'], $user['email']);

                                                                       
                        header("Location: index.php?route=admin/pages"); 
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

<?php
namespace BlogApp\Admin\Controller\AuthController;

use BlogApp\Admin\Controller\AuthPagesController;
use BlogApp\Admin\Repository\AuthRepository\AuthPagesRepository;
use BlogApp\Admin\Controller\SessionController;

class LoginController extends AuthPagesController
{
    protected SessionController $sessionController;

    public function __construct(AuthPagesRepository $authPagesRepository)
    {
        parent::__construct($authPagesRepository);
        $this->sessionController = new SessionController(); 
    }

    public function renderLoginForm()
    {

    //   if ($this->sessionController->isLoggedIn()) {
    //         header('Location: index.php?' . http_build_query(['route' => 'admin/pages']));
    //         return;
    //     }


        $loginError = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $loginError = "Invalid email format.";
                } else {

                    $user = $this->authPagesRepository->getUserByEmail($email);

                    if ($user && password_verify($password, $user['password'])) {
                        
                        $this->sessionController->setUserSession($user['username'], $user['email']);
                        
                        // Redirect to a dashboard or homepage
                        header("Location: index.php?route=admin/pages"); // Example redirect
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
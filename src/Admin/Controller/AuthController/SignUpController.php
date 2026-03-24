<?php
namespace BlogApp\Admin\Controller\AuthController;

use BlogApp\Admin\Controller\AuthPagesController;
use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\Auth\AuthPagesRepository;

class SignUpController extends AuthPagesController
{

    public function __construct(AuthPagesRepository $authPagesRepository, SessionController $sessionController)
    {
        parent::__construct($authPagesRepository, $sessionController); 
    }

    public function renderSignUpForm()
    {

           if ($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession()) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/pages']));
            return;
        }
        $signUpError = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {
            $user_name        = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $email            = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password         = isset($_POST['password']) ? $_POST['password'] : '';
            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            if (! empty($user_name) && ! empty($email) && ! empty($password) && ! empty($confirm_password)) {

                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $signUpError = "Invalid email format.";
                } elseif ($password !== $confirm_password) {
                    $signUpError = "Passwords do not match.";
                } elseif (strlen($password) < 8) {
                    $signUpError = "Password must be at least 8 characters long.";
                } elseif (! preg_match("/[A-Z]/", $password)) {
                    $signUpError = "Password must contain at least one uppercase letter.";
                } elseif (! preg_match("/[0-9]/", $password)) {
                    $signUpError = "Password must contain at least one digit.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    if ($this->authPagesRepository->isEmailOrUsernameTaken($email, $user_name)) {
                        $signUpError = "Username or email already taken.";
                    } else {
                        $success = $this->authPagesRepository->handleSignUp($user_name, $email, $hashedPassword);

                        if ($success) {
                            $this->sessionController->ensureSession();
                            $_SESSION['user_name'] = $user_name;
                            $_SESSION['email']     = $email;
                            $_SESSION['logged_in'] = true;

                            header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                            exit;
                        } else {
                            $signUpError = "An error occurred. Please try again later.";
                        }
                    }
                }

            } else {
                $signUpError = "Please fill in all the required fields.";
            }
        }

        $this->render('auth/signup', [
            'signUpError' => $signUpError,
        ]);
    }

}

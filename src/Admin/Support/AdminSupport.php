<?php
namespace App\Admin\Support;

class AdminSupport
{
    public function ensureSession()
    {
        if (session_id() === '') {
            session_start();
        }
    }


    public function setFlash($key, $message)
    {
        $this->ensureSession();
        $_SESSION['flash'][$key] = $message;
    }


    public function getFlash($key)
    {
        $this->ensureSession();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public function isLoggedIn()
    {
        $this->ensureSession();
        return ! empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Updated to include user_role
    public function setUserSession($user_id, $user_name, $user_email, $user_role)
    {
        $this->ensureSession();
        $_SESSION['user_id']   = $user_id;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['email']     = $user_email;
        $_SESSION['user_role'] = $user_role; // Added role session
        $_SESSION['logged_in'] = true;
        session_regenerate_id();
    }

    public function logout()
    {
        $this->ensureSession();
        // Clear all session data safely
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public function getUserID()
    {
        $this->ensureSession();
        return $_SESSION['user_id'] ?? null;
    }

    public function isAdmin()
    {
        $this->ensureSession();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function isUserIdSession()
    {
        $this->ensureSession();
        return isset($_SESSION['user_id']) && ! empty($_SESSION['user_id']);
    }

}

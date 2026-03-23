<?php
namespace BlogApp\Admin\Controller;

class SessionController
{
    public function ensureSession()
    {
        if (session_id() === '') {
            session_start();  
        }
    }

    public function isLoggedIn()
    {
        $this->ensureSession();  
        return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function setUserSession($user_name, $email)
    {
        $_SESSION['user_name'] = $user_name;
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        session_regenerate_id();  

    }

    public function logout()
    {
        $this->ensureSession();  
        unset($_SESSION['user_name']);  
        unset($_SESSION['logged_in']);
        unset($_SESSION['email']);
        session_regenerate_id();  
    }
}
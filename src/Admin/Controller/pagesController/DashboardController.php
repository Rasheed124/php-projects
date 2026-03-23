<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;
use BlogApp\Admin\Controller\SessionController;

use BlogApp\Admin\Controller\pagesController\PostController;


class DashboardController extends AbstractAdminController
{

    public function __construct( SessionController $sessionController)
    {
        return parent::__construct($sessionController);
    }
    public function renderDashboardPages($page)
    {

        if (empty($this->sessionController->isLoggedIn())) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
            return;
        }

          switch ($page) {

            case 'dashboard':
                $createPostController = new PostController($this->sessionController);
                return $createPostController->dashboard();
                break;

            case 'posts':
                $createPostController = new PostController($this->sessionController);
                return $createPostController->allPost();
                break;

            case 'create':
                $createPostController = new PostController($this->sessionController);
                return $createPostController->createPost();
                break;

    
            default:
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                exit();
        }

    

    }

}

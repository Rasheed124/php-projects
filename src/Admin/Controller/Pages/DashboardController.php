<?php
namespace App\Admin\Controller\Pages;

// use App\Admin\Controller\AbstractAdminController;
use App\Admin\Controller\Pages\AdminPagesController;
use App\Admin\Controller\Post\PostController;
// use App\Frontend\Controller\PagesController;

// use App\Admin\Support\AdminSupport;


class DashboardController 
{

    // public function __construct( AdminSupport $sessionController)
    // {
    //     parent::__construct($sessionController);

    // }

    public function renderDashboardPages($page)
    {

        // if (empty($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession())) {
        //     header('Location: index.php?' . url('admin', 'auth'));
        //     // header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
        //     return;
        // }

        $createPageController = new AdminPagesController();
        $postController       = new PostController();
        switch ($page) {

            case 'dashboard':
                return $createPageController->dashboard();
                break;
            case 'posts':
                return $createPageController->allPages();
                break;
            case 'pages':
                return $postController->allPosts();
                break;
            default:
                header('Location: index.php?' . url('admin', '/dashboard'));
                die();
        }

    }

}

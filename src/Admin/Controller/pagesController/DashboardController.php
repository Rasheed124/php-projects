<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;
use BlogApp\Admin\Controller\pagesController\AdminPagesController;
use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\Admin\AdminPagesRepository;

class DashboardController extends AbstractAdminController
{

    public function __construct(protected AdminPagesRepository $adminPagesRepository, SessionController $sessionController)
    {
        parent::__construct($sessionController);

    }
    public function renderDashboardPages($page)
    {

        if (empty($this->sessionController->isLoggedIn() && $this->sessionController->isUserIdSession())) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
            return;
        }

        switch ($page) {

            case 'dashboard':
                $createPostController = new AdminPagesController($this->sessionController, $this->adminPagesRepository);
                return $createPostController->dashboard();
                break;

            case 'posts':
                $createPostController = new AdminPagesController($this->sessionController, $this->adminPagesRepository);
                return $createPostController->allPost();
                break;

            case 'create':
                $createPostController = new AdminPagesController($this->sessionController, $this->adminPagesRepository);
                return $createPostController->createPost();
                break;

            default:
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                exit();
        }

    }

}

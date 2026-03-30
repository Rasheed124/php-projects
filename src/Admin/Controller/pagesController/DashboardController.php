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

        $createPostController = new AdminPagesController($this->sessionController, $this->adminPagesRepository);
        switch ($page) {

            case 'dashboard':
                return $createPostController->dashboard();
                break;

            case 'posts':
                // Here, we check for the specific post type based on the tab clicked
                $tab = $_GET['tab'] ?? 'all'; // Default to 'all' if no tab is specified
                switch ($tab) {
                    case 'draft':
                        return $createPostController->draftPost(); // For Draft posts
                    case 'pending':
                        return $createPostController->pendingPost(); // For Pending posts
                    case 'all':
                    default:
                        return $createPostController->allPost(); // For All posts
                }
                break;
            case 'create':
                return $createPostController->createPost();
                break;

            default:
                header('Location: index.php?' . http_build_query(['route' => 'admin/auth']));
                die();
        }

    }

}

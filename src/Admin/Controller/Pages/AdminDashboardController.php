<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\ProfileRepository;
use App\Repository\PagesRepository;

class AdminDashboardController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        ProfileRepository $profileRepository,
        protected PagesRepository $pagesRepository,

    ) {
     parent::__construct($sessionController, $profileRepository);
    }

    public function index()
    {

        $error   = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $errors = [];
        $this->render('pages/dashboard', [
            'error'   => $error,
            'success' => $success,
        ]);

    }

}

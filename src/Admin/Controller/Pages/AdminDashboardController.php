<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport; 
use App\Repository\PagesRepository;

class AdminDashboardController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        protected PagesRepository $pagesRepository
    ) {
        parent::__construct($sessionController);
    }

    public function index()
    {

        $this->render('pages/dashboard', []);

    }

}

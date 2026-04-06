<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;

// use App\Repository\Admin\AdminPagesRepository;

class AdminPagesController extends AbstractAdminController
{

    public function dashboard()
    {

        $this->render('pages/dashboard', []);

    }

    public function allPages()
    {
    }

    public function createPages()
    {
    }
    public function editPages()
    {

    }
}

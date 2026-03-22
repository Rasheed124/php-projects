<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;

class DashboardController extends AbstractAdminController
{
    public function dashboardPage()
    {

        $this->render('pages/dashboard', []);

    }

}

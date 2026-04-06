<?php
namespace App\Frontend\Controller;

use App\Frontend\Controller\AbstractFrontendController;

class NotFoundFrontendController extends AbstractFrontendController
{
    public function error404()
    {
        return parent::error404();
    }
}

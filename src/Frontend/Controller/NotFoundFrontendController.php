<?php
namespace BlogApp\Frontend\Controller;

use BlogApp\Frontend\Controller\AbstractFrontendController;

class NotFoundFrontendController extends AbstractFrontendController
{
    public function error404()
    {
        return parent::error404();
    }
}

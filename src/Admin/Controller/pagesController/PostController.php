<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;

class PostController extends AbstractAdminController
{
    public function dashboard()
    {

        $this->render('pages/dashboard', []);
    }
    public function createPost()
    {

        $this->render('pages/create', []);
    }
    public function allPost()
    {

        $this->render('pages/posts', []);
    }

}

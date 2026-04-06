<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Repository\PagesRepository; // Assuming you have an Admin version or use the same one
use App\Admin\Support\AdminSupport;

class AdminPagesController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController, 
        protected PagesRepository $pagesRepository
    ) {
        parent::__construct($sessionController);
    }

    public function handleAction($action)
    {
        switch ($action) {
            case 'index':  return $this->allPages();
            case 'create': return $this->createPage();
            case 'edit':   return $this->editPage();
            default:       return $this->error404();
        }
    }

    public function allPages()
    {
        $pages = $this->pagesRepository->getPages(); 
        $this->render('pages/index', ['pages' => $pages]);
    }
    public function createPage()
    {
        $pages = $this->pagesRepository->getPages(); 
        $this->render('pages/index', ['pages' => $pages]);
    }
    public function editPage()
    {
        $pages = $this->pagesRepository->getPages(); 
        $this->render('pages/index', ['pages' => $pages]);
    }

}
<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Repository\PagesRepository; 
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
        $this->render('pages/all-pages', ['pages' => $pages]);
    }
    public function createPage()
    {
        $pages = $this->pagesRepository->getPages(); 
        $this->render('pages/create-page', ['pages' => $pages]);
    }
    public function editPage()
    {
        $pages = $this->pagesRepository->getPages(); 
        $this->render('pages/edit-page', ['pages' => $pages]);
    }

}
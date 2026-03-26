<?php
namespace BlogApp\Frontend\Controller;

use BlogApp\Admin\Controller\SessionController;
use BlogApp\Frontend\Controller\AbstractFrontendController;
use BlogApp\Repository\PagesRepository;

class PagesController extends AbstractFrontendController
{
    public function __construct(PagesRepository $pagesRepository, SessionController $sessionController)
    {
        parent::__construct($pagesRepository, $sessionController); 
    }

    public function showPage($page)
    {
        $page = $this->pagesRepository->fetchBySlug($page);
        if (empty($page)) {
            $this->error404();
            return;
        }

        switch ($page->slug) {
            case 'index':
                $this->render('pages/index', []);
                break;
            case 'about':
                $this->render('pages/about', []);
                break;
            case 'contact':
                $this->render('pages/contact', []);
                break;
            case 'blog':
                $this->render('pages/blog', []);
                break;
            default:
                $this->error404();
        }
    }
}

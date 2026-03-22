<?php
namespace BlogApp\Frontend\Controller;

use BlogApp\Frontend\Controller\AbstractFrontendController;
use BlogApp\Repository\PagesRepository;
use BlogApp\Admin\Controller\SessionController;

class PagesController extends AbstractFrontendController
{

    // public function __construct(protected PagesRepository $pagesRepository)
    // {}

    protected SessionController $handleIsLoggedIn;
    public function __construct(protected PagesRepository $pagesRepository)
    {
        $this->handleIsLoggedIn =  new SessionController();
    }


    public function showPage($page)
    {
        $page = $this->pagesRepository->fetchBySlug($page);
        if (empty($page)) {
            $this->error404();
            return;
        }

        switch ($page->page_slug) {
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

<?php
namespace App\Frontend\Controller;

use App\Frontend\Controller\AbstractFrontendController;
use App\Repository\PagesRepository;

class PagesController extends AbstractFrontendController
{
    public function __construct(PagesRepository $pagesRepository)
    {
        parent::__construct($pagesRepository); 
    }

    public function showPage($slug)
    {
        $page = $this->pagesRepository->fetchBySlug($slug);

        if (empty($page)) {
            $this->error404();
            return;
        }

        $view = ($slug === 'index') ? 'pages/index' : 'pages/show';

        $this->render($view, [
            'page' => $page
        ]);
    }
}
<?php
namespace App\Frontend\Controller;

use App\Repository\PagesRespository;

class PageController extends AbstractController
{
    public function __construct(protected PagesRespository $pagesRespository)
    {}

    public function showPage($pageKey)
    {
        $page = $this->pagesRespository->fetchBySlug($pageKey);
        if(empty($page)){
            $this->error404();
            return;
        }
        
        $this->render('pages/showPage', [
            'page' => $page,
        ]);
    }

}

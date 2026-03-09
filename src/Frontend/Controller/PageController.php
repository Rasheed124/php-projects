<?php
namespace App\Frontend\Controller;

class PageController extends AbstractController
{
    public function showPage($pageKey)
    {
        $this->render('pages/showPage', []);
    }

}

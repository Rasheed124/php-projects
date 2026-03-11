<?php
namespace App\Admin\Controller;

use App\Repository\PagesRespository;

class PagesAdminController extends AbstractAdminController
{

    public function __construct(private PagesRespository $pagesRespository)
    {}

    public function index()
    {

        $pages = $this->pagesRespository->get();

        $this->render('pages/index', [
            'pages' => $pages,
        ]);
    }
}

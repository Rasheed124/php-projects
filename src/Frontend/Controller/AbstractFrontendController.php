<?php
namespace BlogApp\Frontend\Controller;

use BlogApp\Repository\PagesRepository;

abstract class AbstractFrontendController
{
    public function __construct(protected PagesRepository $pagesRepository)
    {}

    protected function render($view, $params)
    {
        extract($params);

        ob_start();
        require __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $contents = ob_get_clean();

        $navigation = $this->pagesRepository->fetchNavigation();

        var_dump($navigation);

        require __DIR__ . '/../../../views/frontend/layouts/main.view.php';

    }

   

    protected function error404()
    {

        http_response_code(404);
        $this->render('abstract/error404', []);

    }
}

<?php
namespace BlogApp\Frontend\Controller;

use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\PagesRepository;

abstract class AbstractFrontendController
{

    public function __construct(
        protected PagesRepository $pagesRepository, 
        protected SessionController $sessionController)
    {

    
    }
    protected function render($view, $params)
    {
        extract($params);

        ob_start();
        require __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $contents = ob_get_clean();

        $isLoggedIn = $this->sessionController->isLoggedIn();
        $isUserIdSession = $this->sessionController->isUserIdSession();
        $navigation = $this->pagesRepository->fetchNavigation();

        require __DIR__ . '/../../../views/frontend/layouts/main.view.php';

    }

    protected function error404()
    {

        http_response_code(404);
        $this->render('abstract/error404', []);

    }
}

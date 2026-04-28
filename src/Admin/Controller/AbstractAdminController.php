<?php
namespace App\Admin\Controller;

use App\Admin\Support\AdminSupport;
use App\Repository\Admin\ProfileRepository;

abstract class AbstractAdminController
{
    public function __construct(
        protected AdminSupport $sessionController,
        protected ProfileRepository $profileRepository 
    ) {}

    protected function render($view, $params = [])
    {

        $params['authUser'] = $this->profileRepository->getUser();

        $adminSupport = $this->sessionController;
        extract($params);

        ob_start();
        require ADMIN_VIEWS_PATH . '/' . $view . '.view.php';
        $contents = ob_get_clean();

        // The $authUser variable is now available inside main.view.php
        require ADMIN_VIEWS_PATH . '/layouts/main.view.php';
    }

    protected function error404()
    {

        http_response_code(404);
        $this->render('abstract/error404', []);

    }
}

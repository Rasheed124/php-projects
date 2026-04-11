<?php
namespace App\Admin\Controller;

use App\Admin\Support\AdminSupport;
use App\Repository\Admin\ProfileRepository;

abstract class AbstractAdminController
{
    public function __construct(
        protected AdminSupport $sessionController,
        protected ProfileRepository $profileRepository // Added here
    ) {}

    protected function render($view, $params = [])
    {
        // 1. Fetch current user data from the session/database
        // Assuming your sessionController has a method to get the full user object/array
        $params['authUser'] = $this->profileRepository->getUser();

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

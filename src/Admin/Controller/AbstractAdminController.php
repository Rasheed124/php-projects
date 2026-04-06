<?php
namespace App\Admin\Controller;

use App\Admin\Support\AdminSupport;

abstract class AbstractAdminController
{
    public function __construct(protected AdminSupport $sessionController)
    // public function __construct()
    {}

    protected function render($view, $params = [])
    {
        extract($params);

        ob_start();
        require  ADMIN_VIEWS_PATH . '/' . $view . '.view.php';
        $contents = ob_get_clean();

        require ADMIN_VIEWS_PATH . '/layouts/main.view.php';
    }

    protected function error404()
    {

        http_response_code(404);
        $this->render('abstract/error404', []);

    }
}

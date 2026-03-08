<?php
namespace App\Frontend\Controller;

class NotFoundController
{
    public function error404()
    {

        http_response_code(404);
        $this->render('notfound/error404', []);

    }

    private function render($view, $params)
    {

        extract($params);

        ob_start();
        require __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/frontend/layouts/main.view.php';

    }
}

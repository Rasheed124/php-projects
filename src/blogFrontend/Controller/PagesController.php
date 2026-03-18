<?php
namespace App\blogFrontend\Controller;

class PagesController extends AbstractController
{

    public function renderPage($page)
    {
        switch ($page) {
            case 'index':
                return $this->renderIndexPage();
            case 'about':
                return $this->renderAboutPage();
            default:
                return "Page not found.";
        }
    }

    private function renderIndexPage()
    {
        $this->render('pages/index', []);
    }

    private function renderAboutPage()
    {
        return "This is the about page content.";
    }
}

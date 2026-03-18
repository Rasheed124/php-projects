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
            case 'contact':
                return $this->renderContactPage();
            case 'blog':
                return $this->renderBlogPage();
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
        $this->render('pages/about', []);

    }
    private function renderContactPage()
    {
        $this->render('pages/contact', []);

    }
    private function renderBlogPage()
    {
        $this->render('pages/blog', []);

    }
}

<?php
namespace App\Frontend\Controller;

use App\Admin\Support\AdminSupport;
use App\Frontend\Controller\AbstractFrontendController;
use App\Repository\Admin\PostsRepository;
use App\Repository\PagesRepository;

class PagesController extends AbstractFrontendController
{
    public function __construct(
        PagesRepository $pagesRepository,
        AdminSupport $sessionController, protected PostsRepository $postsRepository) {
        parent::__construct($pagesRepository, $sessionController);
    }

    // public function showPage($slug)
    // {
    //     $page = $this->pagesRepository->fetchBySlug($slug);

    //     if (empty($page)) {
    //         $this->error404();
    //         return;
    //     }

    //     $view = ($slug === 'index') ? 'pages/index' : 'pages/show';

    //     $this->render($view, [
    //         'page' => $page,
    //     ]);
    // }

    public function showPage($slug)
    {
        $page = $this->pagesRepository->fetchBySlug($slug);

        if (empty($page)) {
            $this->error404();
            return;
        }

        $data = ['page' => $page];

        // If we are on the homepage, fetch blog content
        if ($slug === 'index') {
            // Fetch 3 posts for the Carousel
            $data['featuredPosts'] = $this->postsRepository->all(['limit' => 3, 'status' => 'published']);

            // Fetch 6 posts for the Main Feed
            $data['latestPosts'] = $this->postsRepository->all(['limit' => 6, 'status' => 'published']);

            // Fetch 5 posts for the Sidebar
            $data['recentPosts'] = $this->postsRepository->all(['limit' => 5, 'status' => 'published']);
        }

        $view = ($slug === 'index') ? 'pages/index' : 'pages/show';

        $this->render($view, $data);
    }
}

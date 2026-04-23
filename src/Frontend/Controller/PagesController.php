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

    public function showPage($slug)
    {
        $page = $this->pagesRepository->fetchBySlug($slug);

        if (! $page) {
            $this->error404();
            return;
        }

        $data = ['page' => $page];

        // logic for specific pages
        if ($slug === 'index') {
            $data['featuredPosts'] = $this->postsRepository->all(['limit' => 3]);
            $data['latestPosts']   = $this->postsRepository->all(['limit' => 6]);
            $data['recentPosts']   = $this->postsRepository->all(['limit' => 3]);
            $data['categories']    = $this->postsRepository->getActiveCategories();
            $data['tags']          = $this->postsRepository->getActiveTags();
            $view                  = 'pages/index';
        } elseif ($slug === 'contact-us') {
            $view = 'pages/contact';
        } else {
            $view = 'pages/show';
        }

        $this->render($view, $data);
    }
    public function showSinglePost($slug)
    {
        $post = $this->postsRepository->fetchBySlug($slug);
        if (! $post) {$this->error404();return;}

        $data = [
            'post'        => $post,
            'page'        => (object) ['slug' => 'blog'],
            'recentPosts' => $this->postsRepository->all(['limit' => 3]),
            'categories'  => $this->postsRepository->getActiveCategories(),
            'tags'        => $this->postsRepository->getActiveTags(),
        ];

        $this->render('pages/post', $data);
    }
}

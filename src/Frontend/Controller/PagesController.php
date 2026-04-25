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

    private function getSidebarData(): array
    {
        return [
            'recentPosts' => $this->postsRepository->all(['limit' => 3]),
            'categories'  => $this->postsRepository->getActiveCategories(),
            'tags'        => $this->postsRepository->getActiveTags(),
        ];
    }

    public function showPage($slug)
    {
        $page = $this->pagesRepository->fetchBySlug($slug);
        if (! $page) {
            $this->error404();
            return;
        }

        $data = array_merge(['page' => $page], $this->getSidebarData());

        if ($slug === 'index') {
            $data['featuredPosts'] = $this->postsRepository->all(['limit' => 3]);
            $data['latestPosts']   = $this->postsRepository->all(['limit' => 6]);
            $view                  = 'pages/index';
        } elseif ($slug === 'contact-us') {
            $view = 'pages/contact';

        } elseif ($slug === 'blog') {

            $limit       = 2;
            $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
            $offset      = ($currentPage - 1) * $limit;

            $totalPosts = $this->postsRepository->getTotalCount();

            $data['posts'] = $this->postsRepository->all([
                'limit'  => $limit,
                'offset' => $offset,
            ]);

            $data['pagination'] = [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ];

            $data['title'] = "Our Blog";
            $view          = 'pages/blog';
        } else {
            $view = 'pages/show';
        }

        $this->render($view, $data);
    }

    public function showSinglePost($slug)
    {
        $post = $this->postsRepository->fetchBySlug($slug);
        if (! $post) {$this->error404();return;}

        $data = array_merge([
            'post' => $post,
            'page' => (object) ['slug' => 'blog'],
        ], $this->getSidebarData());

        $this->render('pages/post', $data);
    }

    public function showByCategory($slug)
    {
        $limit       = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        $filters    = ['category_slug' => $slug];
        $totalPosts = $this->postsRepository->getCountByFilter($filters);

        $posts = $this->postsRepository->allPostByTagAndCat(array_merge($filters, [
            'limit'  => $limit,
            'offset' => $offset,
        ]));

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Category: ' . str_replace('-', ' ', $slug),
            'page'       => (object) ['slug' => 'blog'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/post-list', $data);
    }

    public function showByTag($slug)
    {
        $limit       = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        $filters    = ['tag_slug' => $slug];
        $totalPosts = $this->postsRepository->getCountByFilter($filters);

        $posts = $this->postsRepository->allPostByTagAndCat(array_merge($filters, [
            'limit'  => $limit,
            'offset' => $offset,
        ]));

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Tag: ' . str_replace('-', ' ', $slug),
            'page'       => (object) ['slug' => 'blog'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/post-list', $data);
    }

    public function search()
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';

        // If query is empty, just redirect to blog or show all
        if (empty($query)) {
            header('Location: ' . url('blog'));
            exit;
        }

        $limit       = 10; // You can adjust this
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        // Get filtered posts
        $posts = $this->postsRepository->all([
            'search' => $query,
            'limit'  => $limit,
            'offset' => $offset,
        ]);

        // Get total count for search results specifically
        $totalPosts = $this->postsRepository->getTotalCount(['search' => $query]);

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Search Results for: ' . htmlspecialchars($query),
            'page'       => (object) ['slug' => 'search'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/blog', $data);
    }
}

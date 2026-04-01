<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;
use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\Admin\AdminPagesRepository;

class AdminPagesController extends AbstractAdminController
{
    public function __construct(SessionController $sessionController, protected AdminPagesRepository $adminRepository)
    {
        parent::__construct($sessionController);

    }
    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    public function dashboard()
    {
        $this->render('pages/dashboard', []);
    }

    public function createPost()
    {
        $errors     = [];
        $categories = $this->adminRepository->getCategories();
        $tags       = $this->adminRepository->getTags();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {

            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            if (empty($title)) {
                $errors[] = "Title is required.";
            }

            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            if (empty($content)) {
                $errors[] = "Content is required.";
            }

            $category = isset($_POST['category']) ? $_POST['category'] : '';
            if (empty($category)) {
                $errors[] = "Category is required.";
            } elseif ($category == '0') {
                $errors[] = "No categories found. Please <a href='create-category.php'>create a category</a> first.";
            }

            $slug = isset($_POST['slug']) && ! empty($_POST['slug']) ? trim($_POST['slug']) : null;
            if (empty($slug)) {
                $slug = $this->generateSlug($title);
            }

            $tags = isset($_POST['tags']) ? $_POST['tags'] : [];

            $status = isset($_POST['status']) ? $_POST['status'] : 'draft';

            // Handle file upload for thumbnail
            $thumbnail = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $fileInfo          = pathinfo($_FILES['image']['name']);
                $fileExtension     = strtolower($fileInfo['extension']);
                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Only JPG, PNG, and GIF files are allowed for the thumbnail.";
                }

                if (empty($errors)) {
                    $uploadDir = 'uploads/thumbnails/';
                    $thumbnail = $uploadDir . basename($_FILES['image']['name']);
                    if (! move_uploaded_file($_FILES['image']['tmp_name'], $thumbnail)) {
                        $errors[] = "Failed to upload the thumbnail image.";
                    }
                }
            }

            $userId = $this->sessionController->getUserID();

            // Create post if no errors
            if (empty($errors)) {
                $isCreated = $this->adminRepository->createPost($title, $content, $userId, $category, $slug, $status, $thumbnail, $tags);
                if ($isCreated) {
                    header('Location: index.php?' . http_build_query(['route' => 'admin/pages', 'page' => 'posts']));
                    exit;
                } else {
                    $errors[] = "Failed to create the post.";
                }
            }
        }

        $this->render('pages/create', ['errors' => $errors, 'categories' => $categories, 'tags' => $tags]);
    }

    // Function to fetch posts by status and pass category and user info
    public function allPost()
    {
                      // Pagination
        $limit  = 10; // Number of posts per page
        $page   = (int) ($_GET['page'] ?? 1);
        $page   = max(1, $page);
        $offset = ($page - 1) * $limit;

        // Fetch posts with category and user
        $allPosts   = $this->adminRepository->getPostsWithCategoryAndUser('published', $limit, $offset);
        $totalPosts = $this->adminRepository->getTotalPostsByStatus('published');
                                            // Handle active tab
        $activeTab = $_GET['tab'] ?? 'all'; // Default to 'all' if not specified

        // Pass data to the view
        $this->render('pages/posts', [
            'allPosts'   => $allPosts,
            'totalPosts' => $totalPosts,
            'limit'      => $limit,
            'page'       => $page,
            'activeTab'  => $activeTab, // Add active tab for styling
        ]);
    }

    public function draftPost()
    {
        $limit  = 10;
        $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $draftPosts = $this->adminRepository->getPostsWithCategoryAndUser('draft', $limit, $offset);
        $totalPosts = $this->adminRepository->getTotalPostsByStatus('draft');
        $this->render('pages/posts', [
            'draftPosts' => $draftPosts,
            'totalPosts' => $totalPosts,
            'limit'      => $limit,
            'page'       => $page,
        ]);
    }

    public function pendingPost()
    {
        $limit  = 10;
        $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $pendingPosts = $this->adminRepository->getPostsWithCategoryAndUser('pending', $limit, $offset);
        $totalPosts   = $this->adminRepository->getTotalPostsByStatus('pending');

        $this->render('pages/posts', [
            'pendingPosts' => $pendingPosts,
            'totalPosts'   => $totalPosts,
            'limit'        => $limit,
            'page'         => $page,
        ]);
    }

}

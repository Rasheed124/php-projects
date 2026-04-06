<?php
namespace App\Admin\Controller\Post;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\PostsRepository;
use finfo;

class PostController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        protected PostsRepository $postsRepository
    ) {
        parent::__construct($sessionController);
    }

    public function handleAction($action)
    {
        switch ($action) {
            case 'index':return $this->allPosts();
            case 'create':return $this->createPost();
            case 'edit':return $this->editPost();
            default: return $this->error404();
        }
    }

   
    public function allPosts()
{
    $limit  = 10;
    $page   = max(1, (int) ($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;
    
    // Get status from URL, default to 'all'
    $status = $_GET['status'] ?? 'all'; 

    // Fetch posts based on current tab
    $posts = $this->postsRepository->getPostsWithCategoryAndUser($status, $limit, $offset);
    
    // Fetch counts for the 3 active tabs
    $counts = [
        'all'       => $this->postsRepository->getTotalPostsByStatus('all'),
        'draft'     => $this->postsRepository->getTotalPostsByStatus('draft'),
        'published' => $this->postsRepository->getTotalPostsByStatus('published'),
    ];

    $this->render('pages/posts', [
        'posts'         => $posts,
        'counts'        => $counts,
        'currentStatus' => $status,
        'limit'         => $limit,
        'page'          => $page,
        'totalPosts'    => $counts[$status] ?? 0
    ]);
}

    public function createPost()
    {
        $errors     = [];
        $categories = $this->postsRepository->getCategories();
        $tags       = $this->postsRepository->getTags();

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

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // 1. Configuration
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize       = 2 * 1024 * 1024; // 2MB limit
                $uploadDir         = 'uploads/thumbnails/';

                // 2. Basic File Info
                $fileName      = $_FILES['image']['name'];
                $fileTmpName   = $_FILES['image']['tmp_name'];
                $fileSize      = $_FILES['image']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // 3. Validation Checks
                // Check Extension
                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Invalid file extension. Only JPG, PNG, and GIF are allowed.";
                }

                // Check Actual MIME Type (Security: ensures it's actually an image)
                $finfo    = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($fileTmpName);
                if (! in_array($mimeType, $allowedMimeTypes)) {
                    $errors[] = "The file content is not a valid image.";
                }

                // Check File Size
                if ($fileSize > $maxFileSize) {
                    $errors[] = "The image is too large. Maximum size is 2MB.";
                }

                // 4. Final Processing
                if (empty($errors)) {
                    // Create directory if it doesn't exist
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Generate a unique name to prevent overwriting (e.g., 65a1b2c3d4e5f.png)
                    $uniqueName  = uniqid() . '.' . $fileExtension;
                    $destination = $uploadDir . $uniqueName;

                    if (move_uploaded_file($fileTmpName, $destination)) {
                        $thumbnail = $destination;
                    } else {
                        $errors[] = "Failed to move uploaded file. Check folder permissions.";
                    }
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Handle specific PHP upload errors (e.g., file exceeds server's post_max_size)
                $errors[] = "An error occurred during file upload (Error Code: " . $_FILES['image']['error'] . ").";
            }

            $userId = $this->sessionController->getUserID();

            // Create post if no errors
            if (empty($errors)) {
                $isCreated = $this->postsRepository->createPost($title, $content, $userId, $category, $slug, $status, $thumbnail, $tags);
                if ($isCreated) {
                    header('Location: ' . url('/admin/posts'));
                    exit;
                } else {
                    $errors[] = "Failed to create the post.";
                }
            }
        }

        $this->render('pages/create-post', ['errors' => $errors, 'categories' => $categories, 'tags' => $tags]);
    }

    public function editPost()
    {
        $this->render('pages/index');
    }

    // HELPER FUNCTIONS
    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }
}

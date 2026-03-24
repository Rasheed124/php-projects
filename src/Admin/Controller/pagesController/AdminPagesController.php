<?php
namespace BlogApp\Admin\Controller\pagesController;

use BlogApp\Admin\Controller\AbstractAdminController;
use BlogApp\Admin\Controller\SessionController;
use BlogApp\Repository\Admin\AdminPagesRepository;

class AdminPagesController extends AbstractAdminController
{

    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    public function __construct(SessionController $sessionController, protected AdminPagesRepository $adminRepository)
    {
        parent::__construct($sessionController);

    }
    public function dashboard()
    {
        $this->render('pages/dashboard', []);
    }
    public function createPost()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {

            // Validate title
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            if (empty($title)) {
                $errors[] = "Title is required.";
            }

            // Validate content
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            if (empty($content)) {
                $errors[] = "Content is required.";
            }

            // Validate category
            $category = isset($_POST['category']) ? $_POST['category'] : '';
            if (empty($category)) {
                $errors[] = "Category is required.";
            }

            // Slug handling
            $slug = isset($_POST['slug']) && ! empty($_POST['slug']) ? trim($_POST['slug']) : null;
            if (empty($slug)) {
                // Generate slug from title if not provided
                $slug = $this->generateSlug($title);
            }

            // Validate status
            $status = isset($_POST['status']) ? $_POST['status'] : 'draft';

            // Handle file upload for thumbnail
            $thumbnail = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Validate image upload
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $fileInfo          = pathinfo($_FILES['image']['name']);
                $fileExtension     = strtolower($fileInfo['extension']);
                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Only JPG, PNG, and GIF files are allowed for the thumbnail.";
                }

                // Move the uploaded file to a target directory
                if (empty($errors)) {
                    $uploadDir = 'uploads/thumbnails/';
                    $thumbnail = $uploadDir . basename($_FILES['image']['name']);
                    if (! move_uploaded_file($_FILES['image']['tmp_name'], $thumbnail)) {
                        $errors[] = "Failed to upload the thumbnail image.";
                    }
                }
            }

            $authorId = $this->sessionController->getUSerID();

            // Create post
            if (empty($errors)) {
                $isCreated = $this->adminRepository->createPost($title, $content, $authorId, $category, $slug, $status, $thumbnail);

                if ($isCreated) {
                    // Redirect or notify success
                    header('Location: index.php?' . http_build_query(['route' => 'admin/pages', 'page' => 'posts']));
                    exit;
                } else {
                    $errors[] = "Failed to create the post.";
                }
            }
        }

        $this->render('pages/create', ['errors' => $errors]);
    }

    public function allPost()
    {

        $this->render('pages/posts', []);
    }

}

<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\PagesRepository;
use finfo;

class AdminPagesController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        protected PagesRepository $pagesRepository
    ) {
        parent::__construct($sessionController);
    }

    public function handleAction($action)
    {
        $this->ensureAdmin();

        switch ($action) {
            case 'index':return $this->allPages();
            case 'create':return $this->createPage();
            case 'edit':return $this->editPage();
            case 'delete':return $this->deletePage(); // Added
            default: return $this->error404();
        }
    }

    public function allPages()
    {
        $limit  = 10;
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $status = $_GET['status'] ?? 'all';

        $error   = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $pages = $this->pagesRepository->getPagesWithUser($status, $limit, $offset);

        $counts = [
            'all'       => $this->pagesRepository->getTotalPagesByStatus('all'),
            'published' => $this->pagesRepository->getTotalPagesByStatus('published'),
            'draft'     => $this->pagesRepository->getTotalPagesByStatus('draft'),
        ];

        $this->render('pages/all-pages', [
            'pages'         => $pages,
            'counts'        => $counts,
            'currentStatus' => $status,
            'limit'         => $limit,
            'page'          => $page,
            'totalPages'    => $counts[$status] ?? 0,
            'error'         => $error,
            'success'       => $success,
        ]);
    }

    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    private function ensureAdmin()
    {
        if (! $this->sessionController->isAdmin()) {
            $_SESSION['error'] = "Access Denied: Only administrators can manage pages.";
            header("Location: " . url('admin/dashboard'));
            exit;
        }
    }

    public function createPage()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {
            $title   = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $slug    = $this->generateSlug($title);

            // Validations
            if (empty($title)) {
                $errors[] = "Title is required.";
            }

            if (empty($content)) {
                $errors[] = "Content is required.";
            }

            $status = isset($_POST['status']) ? $_POST['status'] : 'draft';

            // Handle file upload for thumbnail
            $thumbnail = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize       = 2 * 1024 * 1024;
                $uploadDir         = 'uploads/pages/'; // Separate folder for page images

                $fileTmpName   = $_FILES['image']['tmp_name'];
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Invalid file extension.";
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (! in_array($finfo->file($fileTmpName), $allowedMimeTypes)) {
                    $errors[] = "Invalid image content.";
                }

                if ($_FILES['image']['size'] > $maxFileSize) {
                    $errors[] = "Image must be less than 2MB.";
                }

                if (empty($errors)) {
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $thumbnail = $uploadDir . uniqid() . '.' . $fileExtension;
                    move_uploaded_file($fileTmpName, $thumbnail);
                }
            }

            if (empty($errors)) {
                $userId    = $this->sessionController->getUserID();
                $isCreated = $this->pagesRepository->createPage($title, $slug, $content, $status, $thumbnail, $userId);

                if ($isCreated) {
                    $_SESSION['success'] = "Page created successfully.";
                    header('Location: ' . url('admin/pages/index'));
                    exit;
                } else {
                    $errors[] = "Database error: Could not save the page.";
                }
            }
        }

        $this->render('pages/create-page', ['errors' => $errors]);
    }

    public function deletePage()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0 && $this->pagesRepository->deletePage($id)) {
            $_SESSION['success'] = "Page permanently deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete page.";
        }
        header('Location: ' . url('admin/pages/index'));
        exit;
    }

    public function editPage()
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $page = $this->pagesRepository->fetchById($id);

        if (! $page) {
            $_SESSION['error'] = "Page not found.";
            header('Location: ' . url('admin/pages/index'));
            exit;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST)) {
            $title   = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status  = $_POST['status'] ?? 'draft';
            $slug    = $this->generateSlug($title);

            if (empty($title)) {
                $errors[] = "Title is required.";
            }

            if (empty($content)) {
                $errors[] = "Content is required.";
            }

            // Image Handling
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

            if (empty($errors)) {
                if ($this->pagesRepository->updatePage($id, $title, $slug, $content, $status, $thumbnail)) {
                    $_SESSION['success'] = "Page updated successfully.";
                    header('Location: ' . url('admin/pages/index'));
                    exit;
                } else {
                    $errors[] = "Failed to update page.";
                }
            }
        }

        $this->render('pages/edit-page', [
            'errors' => $errors,
            'page'   => $page,
        ]);
    }

}

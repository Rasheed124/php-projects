<?php
namespace App\Admin\Controller\Post;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\PostsRepository;

class TaxonomyController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        protected PostsRepository $postsRepository
    ) {
        parent::__construct($sessionController);
    }

    public function handleAction($action)
    {
        $this->ensureAdmin();

        switch ($action) {
            case 'index':
                return $this->listAll();

            // --- CATEGORY ROUTES ---
            case 'categories/save':
                return $this->saveCategory();
            case 'categories/delete':
                return $this->deleteCategory();

            // --- TAG ROUTES ---
            case 'tags/save':
                return $this->saveTag();
            case 'tags/delete':
                return $this->deleteTag();

            default:
                header("Location: " . url('admin/dashboard'));
                exit;
        }
    }

    private function ensureAdmin()
    {
        if (! $this->sessionController->isAdmin()) {
            $_SESSION['error'] = "Access Denied: Only administrators can manage categories and tags.";
            header("Location: " . url('admin/dashboard'));
            exit;
        }
    }

    public function listAll()
    {
        $error   = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $this->render('pages/create-category-and-tag', [
            'categories' => $this->postsRepository->getCategories(),
            'tags'       => $this->postsRepository->getTags(),
            'error'      => $error,
            'success'    => $success,
        ]);
    }

    // --- CATEGORY LOGIC ---
    public function saveCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $id   = (int) ($_POST['id'] ?? 0);
            $slug = $this->generateSlug($name);

            if (empty($name)) {
                $_SESSION['error'] = "Category name is required.";
            } else {
                if ($id > 0) {
                    $this->postsRepository->updateCategory($id, $name, $slug);
                    $_SESSION['success'] = "Category updated.";
                } else {
                    $this->postsRepository->createCategory($name, $slug);
                    $_SESSION['success'] = "Category created.";
                }
            }
        }
        header("Location: " . url('admin/taxonomy'));
        exit;
    }

    public function deleteCategory()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->postsRepository->deleteCategory($id)) {
            $_SESSION['success'] = "Category removed.";
        } else {
            $_SESSION['error'] = "Cannot delete category (it might be in use).";
        }
        header("Location: " . url('admin/taxonomy'));
        exit;
    }

    // --- TAG LOGIC ---
    public function saveTag()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $id   = (int) ($_POST['id'] ?? 0);

            if (empty($name)) {
                $_SESSION['error'] = "Tag name is required.";
            } else {
                if ($id > 0) {
                    $this->postsRepository->updateTag($id, $name);
                    $_SESSION['success'] = "Tag updated.";
                } else {
                    $this->postsRepository->createTag($name);
                    $_SESSION['success'] = "Tag created.";
                }
            }
        }
        header("Location: " . url('admin/taxonomy'));
        exit;
    }

    public function deleteTag()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->postsRepository->deleteTag($id)) {
            $_SESSION['success'] = "Tag removed.";
        } else {
            $_SESSION['error'] = "Failed to remove tag.";
        }
        header("Location: " . url('admin/taxonomy'));
        exit;
    }

    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }
}

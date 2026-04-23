<?php
namespace App\Admin\Controller\Post;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\PostsRepository;
use App\Repository\Admin\ProfileRepository;
use finfo;

class PostController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        ProfileRepository $profileRepository,
        private PostsRepository $postsRepository 
    ) {
        parent::__construct($sessionController, $profileRepository);
    }

    public function handleAction($action)
    {
        switch ($action) {
            case 'index':return $this->allPosts();
            case 'trash':return $this->trashPost();
            case 'restore':return $this->restorePost();
            case 'delete':return $this->deletePost();
            case 'create':return $this->createPost();
            case 'edit':return $this->editPost();
            case 'upload-post-image':return $this->uploadPostImage();
            default: return $this->error404();
        }
    }

    public function allPosts()
    {
        $limit  = 7;
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        $status = $_GET['status'] ?? 'all';

        // Capture flash messages from session
        $error   = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        // Clear them immediately so they don't show again on refresh
        unset($_SESSION['error'], $_SESSION['success']);

        $posts = $this->postsRepository->getPostsWithCategoryAndUser($status, $limit, $offset);

        $counts = [
            'all'       => $this->postsRepository->getTotalPostsByStatus('all'),
            'draft'     => $this->postsRepository->getTotalPostsByStatus('draft'),
            'published' => $this->postsRepository->getTotalPostsByStatus('published'),
            'trash'     => $this->postsRepository->getTotalPostsByStatus('trash'),
        ];

        $this->render('pages/posts', [
            'posts'         => $posts,
            'counts'        => $counts,
            'currentStatus' => $status,
            'limit'         => $limit,
            'page'          => $page,
            'totalPosts'    => $counts[$status] ?? 0,
            'error'         => $error,   // Pass to view
            'success'       => $success, // Pass to view
        ]);
    }

    public function createPost()
    {
        $errors       = [];
        $categories   = $this->postsRepository->getCategories();
        $tags         = $this->postsRepository->getTags();
        $selectedTags = [];

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

            if (! empty($slug) && $this->postsRepository->slugExists($slug)) {
                $errors[] = "The slug '{$slug}' is already in use. Please provide a unique slug or title.";
            }

            $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];

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
                $isCreated = $this->postsRepository->createPost($title, $content, $userId, $category, $slug, $status, $thumbnail, $selectedTags);
                if ($isCreated) {
                    header('Location: ' . url('/admin/posts'));
                    exit;
                } else {
                    $errors[] = "Failed to create the post.";
                }
            }
        }

        $this->render('pages/create-post', ['errors' => $errors, 'categories' => $categories, 'tags' => $tags, 'selectedTags' => $selectedTags]);
    }

    public function editPost()
    {
        $id = (int) ($_GET['id'] ?? 0);

        /** * 1. SECURITY & EXISTENCE CHECK
         * This single line replaces your manual ID check and findById logic.
         * It will handle redirects and error messages automatically.
         */
        $post = $this->checkPostAccess($id);

        // If we reached here, the post exists AND the user is authorized.
        $errors       = [];
        $categories   = $this->postsRepository->getCategories();
        $tags         = $this->postsRepository->getTags();
        $selectedTags = $post['tag_ids'] ?? [];

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
                $errors[] = "No categories found. Please create a category first.";
            }

            $slug = isset($_POST['slug']) && ! empty($_POST['slug']) ? trim($_POST['slug']) : null;
            if (empty($slug)) {
                $slug = $this->generateSlug($title);
            }

            if ($this->postsRepository->slugExistsExcluding($slug, (int) $id)) {
                $errors[] = "The slug '{$slug}' is already in use by another post.";
            }

            $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];
            $status       = isset($_POST['status']) ? $_POST['status'] : 'draft';

            // Handle file upload logic for thumbnail
            $thumbnail = $post['thumbnail'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize       = 2 * 1024 * 1024;
                $uploadDir         = 'uploads/thumbnails/';

                $fileName      = $_FILES['image']['name'];
                $fileTmpName   = $_FILES['image']['tmp_name'];
                $fileSize      = $_FILES['image']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Invalid file extension.";
                }

                $finfo    = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($fileTmpName);
                if (! in_array($mimeType, $allowedMimeTypes)) {
                    $errors[] = "The file content is not a valid image.";
                }

                if ($fileSize > $maxFileSize) {
                    $errors[] = "The image is too large (Max 2MB).";
                }

                if (empty($errors)) {
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $uniqueName  = uniqid() . '.' . $fileExtension;
                    $destination = $uploadDir . $uniqueName;

                    if (move_uploaded_file($fileTmpName, $destination)) {
                        if (! empty($post['thumbnail']) && file_exists($post['thumbnail'])) {
                            unlink($post['thumbnail']);
                        }
                        $thumbnail = $destination;
                    } else {
                        $errors[] = "Failed to move uploaded file.";
                    }
                }
            }

            // 2. UPDATE POST
            if (empty($errors)) {
                $updateData = [
                    'title'       => $title,
                    'content'     => $content,
                    'category_id' => $category,
                    'slug'        => $slug,
                    'status'      => $status,
                    'thumbnail'   => ($thumbnail !== $post['thumbnail']) ? $thumbnail : null,
                ];

                $isUpdated = $this->postsRepository->updatePost((int) $id, $updateData, $selectedTags);

                if ($isUpdated) {
                    $_SESSION['success'] = "Post updated successfully.";
                    header('Location: ' . url('admin/posts'));
                    exit;
                } else {
                    $errors[] = "Failed to update the post.";
                }
            }
        }

        $this->render('pages/edit-post', [
            'errors'       => $errors,
            'post'         => $post,
            'categories'   => $categories,
            'tags'         => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }

    private function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    public function trashPost()
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $post = $this->checkPostAccess($id);

        $isAdmin = $this->sessionController->isAdmin();
        $userId  = (int) $this->sessionController->getUserID();

        // 1. Prepare data to change status to draft upon trashing
        $trashData = [
            'status' => 'draft',
        ];

        // 2. We update the status AND then call softDelete
        // (Assuming your repository allows passing data to softDelete or has an update method)
        $this->postsRepository->updatePost($id, $trashData, $post['tag_ids'] ?? []);

        if ($this->postsRepository->softDelete($id, $userId, $isAdmin)) {
            $_SESSION['success'] = "Post moved to trash and set to draft.";
        } else {
            $_SESSION['error'] = "Unable to move post to trash.";
        }

        header("Location: " . url('admin/posts'));
        exit;
    }

    public function restorePost()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $post = $this->checkPostAccess($id);

        $isAdmin = $this->sessionController->isAdmin();
        $userId  = (int) $this->sessionController->getUserID();

        if ($this->postsRepository->restore($id, $userId, $isAdmin)) {
            $_SESSION['success'] = "Post restored successfully.";
        } else {
            $_SESSION['error'] = "Unable to restore post.";
        }

        header("Location: " . url('admin/posts') . "?status=trash");
        exit;
    }

    public function deletePost()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $post = $this->checkPostAccess($id);

        $isAdmin = $this->sessionController->isAdmin();
        $userId  = (int) $this->sessionController->getUserID();

        if ($this->postsRepository->permanentDelete($id, $userId, $isAdmin)) {
            $_SESSION['success'] = "Post deleted permanently.";
        } else {
            $_SESSION['error'] = "Unable to delete post.";
        }

        header("Location: " . url('admin/posts') . "?status=trash");
        exit;
    }

    private function checkPostAccess($id)
    {
        if ($id <= 0) {
            $_SESSION['error'] = "Invalid Post ID.";
            header("Location: " . url('admin/posts'));
            exit;
        }

        // Fetch post from repository (ensure you have a findById method)
        $post = $this->postsRepository->findById($id);

        if (! $post) {
            $_SESSION['error'] = "Post not found.";
            header("Location: " . url('admin/posts'));
            exit;
        }

        // AUTHORITY LOGIC
        $isAdmin       = $this->sessionController->isAdmin();
        $currentUserId = (int) $this->sessionController->getUserID();
        $postOwnerId   = (int) $post['user_id'];

        // If you aren't an admin AND you don't own the post, block access
        if (! $isAdmin && ($currentUserId !== $postOwnerId)) {
            $_SESSION['error'] = "Access Denied: You cannot modify posts created by others.";
            header("Location: " . url('admin/posts'));
            exit;
        }

        return $post;
    }

    private function uploadPostImage()
    {
        if ($_FILES['file']) {
            $file        = $_FILES['file'];
            $uploadDir   = 'uploads/posts/';
            $fileName    = uniqid() . '_' . $file['name'];
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // TinyMCE expects a JSON response with the location
                echo json_encode(['location' => url($destination)]);
            }
        }
    }

    // =================================== CATEGORY AND TAGS ========================== \\
    //============================================================================== \\

}

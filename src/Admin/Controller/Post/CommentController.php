<?php
namespace App\Admin\Controller\Post;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\PostsRepository;
use App\Repository\Admin\ProfileRepository;

class CommentController extends AbstractAdminController
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
        $parts      = explode('/', $action);
        $baseAction = $parts[0] ?: 'index';
        $id         = $_GET['id'] ?? ($parts[1] ?? null);

        switch ($baseAction) {
            case 'index':return $this->index();
            case 'store':return $this->store();
            case 'approve':return $this->updateStatus($id, 1);
            case 'reject':return $this->updateStatus($id, 0);
            case 'delete':return $this->delete($id);
            case 'edit':return $this->edit($id);     // Show edit form
            case 'update':return $this->update($id); // Handle form submission
            default: return $this->error404();
        }
    }

    private function index()
    {
        $status = $_GET['status'] ?? 'all';
        $page   = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $currentUserId = $this->sessionController->isAdmin() ? null : $this->sessionController->getUserID();

        // Fetch comments filtered by post ownership if not admin
        $comments = $this->postsRepository->getAdminCommentsGrouped($status, $limit, $offset, $currentUserId);
        $total    = $this->postsRepository->getAdminCommentsCount($status, $currentUserId);

        $this->render('pages/comments/index', [
            'comments'      => $comments,
            'currentStatus' => $status,
            'page'          => $page,
            'limit'         => $limit,
            'totalPages'    => $total,
            'counts'        => $this->postsRepository->getCommentStatusCounts($currentUserId),
        ]);
    }

    private function updateStatus($id, $status)
    {
        $this->checkCommentAccess($id);
        $this->postsRepository->updateCommentStatus($id, $status);

        $_SESSION['success'] = "Comment status updated successfully.";
        header("Location: " . url('admin/comments/index'));
        exit;
    }

    private function delete($id)
    {
        $this->checkCommentAccess($id);
        $this->postsRepository->deleteComment($id);

        $_SESSION['success'] = "Comment deleted permanently.";
        header("Location: " . url('admin/comments/index'));
        exit;
    }

    private function checkCommentAccess($commentId)
    {
        $comment = $this->postsRepository->getCommentWithPostAuthor($commentId);

        if (! $comment) {
            $_SESSION['error'] = "Comment not found.";
            header("Location: " . url('admin/comments/index'));
            exit;
        }

        $isAdmin       = $this->sessionController->isAdmin();
        $currentUserId = (int) $this->sessionController->getUserID();
        $postOwnerId   = (int) $comment['post_author_id'];

        if (! $isAdmin && ($currentUserId !== $postOwnerId)) {
            $_SESSION['error'] = "Access Denied: You can only moderate comments on your own posts.";
            header("Location: " . url('admin/comments/index'));
            exit;
        }

        return $comment;
    }

    private function edit($id)
    {
        $comment = $this->checkCommentAccess($id);

        $this->render('pages/comments/edit', [
            'comment' => $comment,
        ]);
    }

    private function update($id)
    {
        $this->checkCommentAccess($id);

        $commentText = trim($_POST['comment'] ?? '');
        $replyText   = trim($_POST['reply'] ?? '');

        if (empty($commentText)) {
            $_SESSION['error'] = "Comment content cannot be empty.";
            header("Location: " . url("admin/comments/edit?id=$id"));
            exit;
        }

        $this->postsRepository->updateCommentAndReply($id, $commentText, $replyText);

        $_SESSION['success'] = "Comment and response updated.";
        header("Location: " . url('admin/comments/index'));
        exit;
    }

    public function store()
    {
        header('Content-Type: application/json');

        // 1. Request Method Validation
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
            exit;
        }

        // 2. Input Collection & Strict Sanitization
        $postId = (int) ($_POST['post_id'] ?? 0);

        // Ensure parent_id is null if empty, 0, or non-numeric
        $rawParentId = $_POST['parent_id'] ?? '';
        $parentId    = (is_numeric($rawParentId) && (int) $rawParentId > 0) ? (int) $rawParentId : null;

        $message = trim($_POST['message'] ?? '');
        $userId  = $this->sessionController->getUserID();
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');

        // 3. Essential Validations
        if ($postId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Post reference is missing.']);
            exit;
        }

        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Please enter a comment message.']);
            exit;
        }

        // Check if the post actually exists before attempting to save a comment
        $post = $this->postsRepository->findById($postId);
        if (! $post) {
            echo json_encode(['success' => false, 'error' => 'The post you are commenting on does not exist.']);
            exit;
        }

        // Guest Validation
        if (! $userId) {
            if (empty($name) || empty($email)) {
                echo json_encode(['success' => false, 'error' => 'Name and Email are required for guests.']);
                exit;
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => 'Please provide a valid email address.']);
                exit;
            }

            // Persist guest info in session for "Show pending after refresh" feature
            $_SESSION['guest_email'] = $email;
            $_SESSION['guest_name']  = $name;
        }

        // 4. Auto-Approval Logic
        $isApproved = 0;
        // Auto-approve if user is Admin OR the owner of the post being commented on
        if ($userId && ($this->sessionController->isAdmin() || $userId == $post['user_id'])) {
            $isApproved = 1;
        }

        // 5. Data Preparation
        $data = [
            'post_id'     => $postId,
            'parent_id'   => $parentId, // Strictly integer or null
            'user_id'     => $userId,   // null for guests
            'guest_name'  => $userId ? null : $name,
            'guest_email' => $userId ? null : $email,
            'comment'     => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
            'is_approved' => $isApproved,
        ];

        // 6. Database Execution
        try {
            $commentId = $this->postsRepository->saveComment($data);

            if ($commentId) {
                echo json_encode([
                    'success'  => true,
                    'message'  => $isApproved ? 'Comment posted successfully!' : 'Comment submitted and awaiting moderation.',
                    'approved' => $isApproved,
                    'data'     => [
                        'id'      => $commentId,
                        'name'    => $userId ? $this->sessionController->getUserName() : $name,
                        'comment' => $data['comment'],
                        'date'    => date('M d, Y'),
                    ],
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save comment to database.']);
            }
        } catch (\Exception $e) {
            // Log error here in a real production app: error_log($e->getMessage());
            echo json_encode(['success' => false, 'error' => 'A server error occurred. Please try again later.']);
        }
        exit;
    }

    // public function store()
    // {
    //     header('Content-Type: application/json');

    //     // 1. Request Method Validation
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    //         exit;
    //     }

    //     // 2. Input Collection & Sanitization
    //     $postId   = (int) ($_POST['post_id'] ?? 0);
    //     $parentId = ! empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;
    //     $message  = trim($_POST['message'] ?? '');

    //     $userId = $this->sessionController->getUserID();
    //     $name   = trim($_POST['name'] ?? '');
    //     $email  = trim($_POST['email'] ?? '');

    //     // 3. Validation
    //     if (empty($message)) {
    //         echo json_encode(['success' => false, 'error' => 'Message cannot be empty.']);
    //         exit;
    //     }

    //     if ($postId <= 0) {
    //         echo json_encode(['success' => false, 'error' => 'Invalid post ID.']);
    //         exit;
    //     }

    //     if (! $userId) {
    //         if (empty($name) || empty($email)) {
    //             echo json_encode(['success' => false, 'error' => 'Name and Email are required.']);
    //             exit;
    //         }
    //         if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //             echo json_encode(['success' => false, 'error' => 'Invalid email format.']);
    //             exit;
    //         }

    //         // PERSISTENCE FIX: Store guest email in session so the repository
    //         // can fetch their pending comments after a refresh.
    //         $_SESSION['guest_email'] = $email;
    //         $_SESSION['guest_name']  = $name;
    //     }

    //     // 4. Approval Logic
    //     $isApproved = 0;
    //     $post       = $this->postsRepository->findById($postId);

    //     // Auto-approve if user is Admin or the Post Author
    //     if ($userId && ($this->sessionController->isAdmin() || $userId == $post['user_id'])) {
    //         $isApproved = 1;
    //     }

    //     // 5. Data Preparation
    //     $data = [
    //         'post_id'     => $postId,
    //         'parent_id'   => $parentId,
    //         'user_id'     => $userId,
    //         'guest_name'  => $userId ? null : $name,
    //         'guest_email' => $userId ? null : $email,
    //         'comment'     => htmlspecialchars($message), // Security scrubbing
    //         'is_approved' => $isApproved,
    //     ];

    //     // 6. Database Execution
    //     try {
    //         $save = $this->postsRepository->saveComment($data);

    //         if ($save) {
    //             echo json_encode([
    //                 'success'  => true,
    //                 'message'  => $isApproved ? 'Comment posted!' : 'Comment submitted for moderation.',
    //                 'approved' => $isApproved,
    //                 // Return data for immediate AJAX display
    //                 'data'     => [
    //                     'name'    => $userId ? $this->sessionController->getUserName() : $name,
    //                     'comment' => $data['comment'],
    //                     'date'    => date('M d, Y'),
    //                 ],
    //             ]);
    //         } else {
    //             echo json_encode(['success' => false, 'error' => 'Could not save comment.']);
    //         }
    //     } catch (\Exception $e) {
    //         echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    //     }
    //     exit;
    // }

    // private function ensureAdmin()
    // {
    //     if (! $this->sessionController->isAdmin()) {
    //         $_SESSION['error'] = "Access Denied: Only administrators can manage categories and tags.";
    //         header("Location: " . url('admin/dashboard'));
    //         exit;
    //     }
    // }
}

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
        // Default to 'all', or specific enum values
        $status = $_GET['status'] ?? 'all';
        $page   = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $currentUserId = $this->sessionController->isAdmin() ? null : $this->sessionController->getUserID();

        // Updated Repository calls to handle enum logic
        $comments = $this->postsRepository->getAdminCommentsGrouped($status, $limit, $offset, $currentUserId);
        $total    = $this->postsRepository->getAdminCommentsCount($status, $currentUserId);

        $this->render('pages/comments/index', [
            'comments'      => $comments,
            'currentStatus' => $status,
            'page'          => $page,
            'limit'         => $limit,
            'totalPages'    => ceil($total / $limit),
            'counts'        => $this->postsRepository->getCommentStatusCounts($currentUserId),
        ]);
    }
    // private function updateStatus($id, $status)
    // {
    //     $this->checkCommentAccess($id);
    //     $this->postsRepository->updateCommentStatus($id, $status);

    //     $_SESSION['success'] = "Comment status updated successfully.";
    //     header("Location: " . url('admin/comments/index'));
    //     exit;
    // }

    private function updateStatus($id, $actionType)
    {
        $this->checkCommentAccess($id);

        // Map 1/0 to enum 'approved'/'pending'
        $newStatus = ($actionType == 1) ? 'approved' : 'pending';

        $this->postsRepository->updateCommentStatus($id, $newStatus);

        $_SESSION['success'] = "Comment marked as " . ucfirst($newStatus);
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

        // Get the current admin/user ID from the session via the support controller
        $currentAdminId = $this->sessionController->getUserID();

        // Pass the admin ID so the repository can assign it to the new reply row
        $updated = $this->postsRepository->updateCommentAndReply($id, $commentText, $replyText, $currentAdminId);

        if ($updated) {
            $_SESSION['success'] = "Comment and response updated successfully.";
        } else {
            $_SESSION['error'] = "Something went wrong while updating the comment.";
        }

        header("Location: " . url('admin/comments/index'));
        exit;
    }
    public function store()
    {
        header('Content-Type: application/json');

        // 1. Gather Basic Info
        $currentUserId = $this->sessionController->getUserID();
        $postId        = (int) ($_POST['post_id'] ?? 0);
        $message       = trim($_POST['message'] ?? '');
        $parentId      = ! empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        // 2. Fetch Post to check ownership for auto-approval
        $post = $this->postsRepository->findById($postId);
        if (! $post) {
            echo json_encode(['success' => false, 'error' => 'Post not found.']);
            exit;
        }

        // 3. Validation
        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Comment cannot be empty.']);
            exit;
        }

        // 4. Determine Status (Auto-approve if Admin OR Post Owner)
        $isAdmin     = $this->sessionController->isAdmin();
        $isPostOwner = ($currentUserId !== null && $post['user_id'] == $currentUserId);
        $status      = ($isAdmin || $isPostOwner) ? 'approved' : 'pending';

        // 5. Prepare Data Object
        $data = [
            'post_id'      => $postId,
            'parent_id'    => $parentId,
            'content'      => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
            'user_id'      => $currentUserId ?: null,
            'author_name'  => $currentUserId ? null : trim($_POST['name'] ?? ''),
            'author_email' => $currentUserId ? null : trim($_POST['email'] ?? ''),
            'status'       => $status,
        ];

        // 6. Guest Security Check
        if (! $currentUserId) {
            if (empty($data['author_name']) || empty($data['author_email'])) {
                echo json_encode(['success' => false, 'error' => 'Name and Email are required for guests.']);
                exit;
            }
            // CRITICAL: Save guest email to session for persistence/viewing unapproved comments
            $_SESSION['guest_email'] = $data['author_email'];
        }

        // 7. Database Execution
        $id = $this->postsRepository->save($data);

        if ($id) {
            echo json_encode([
                'success' => true,
                'message' => ($status === 'approved') ? 'Comment posted!' : 'Comment submitted and awaiting moderation.',
                'data'    => [
                    'name'     => $currentUserId ? $this->sessionController->getUserName() : $data['author_name'],
                    'content'  => $data['content'],
                    'date'     => date('M d, Y'),
                    'status'   => $data['status'],
                    'approved' => ($status === 'approved'),
                ],
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'A server error occurred. Please try again later.']);
        }
    }

}

<?php
namespace App\Admin\Controller\Pages;

use App\Admin\Controller\AbstractAdminController;
use App\Admin\Support\AdminSupport;
use App\Repository\Admin\ProfileRepository;
use finfo;

class AdminProfileController extends AbstractAdminController
{
    public function __construct(
        AdminSupport $sessionController,
        ProfileRepository $profileRepository,
    ) {
        parent::__construct($sessionController, $profileRepository);
    }

    public function handleAction($action)
    {

        switch ($action) {
            case 'index':return $this->allProfilePage();
            case 'edit':return $this->editProfile();
            case 'view':return $this->profilePage();
            case 'trash':return $this->trashProfile();
            case 'restore':return $this->restoreProfile();
            case 'delete':return $this->deleteProfile();
            default: return $this->error404();

        }
    }

    public function allProfilePage()
    {
        $status = $_GET['status'] ?? 'all'; // 'all' or 'trash'
        $page   = (int) ($_GET['page'] ?? 1);
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $users      = $this->profileRepository->getAll($status === 'trash' ? 'trash' : 'active', $offset, $limit);
        $totalUsers = $this->profileRepository->getCount($status === 'trash' ? 'trash' : 'active');

        $this->render('pages/all-profile', [
            'users'         => $users,
            'currentStatus' => $status,
            'totalUsers'    => $totalUsers,
            'page'          => $page,
            'limit'         => $limit,
            'counts'        => [
                'all'   => $this->profileRepository->getCount('active'),
                'trash' => $this->profileRepository->getCount('trash'),
            ],
        ]);
    }

    public function trashProfile()
    {
        $this->ensureAdmin(); // Only admin can trash
        $id = (int) $_GET['id'];

        if ($this->profileRepository->trash($id)) {
            $_SESSION['success'] = "User has been moved to trash and login access disabled.";
        }
        header("Location: " . url('admin/profile/index'));
        exit;
    }

    public function restoreProfile()
    {
        $this->ensureAdmin();
        $id      = (int) $_GET['id'];
        $newPass = "Welcome" . rand(1000, 9999); // Generate the generic password

        if ($this->profileRepository->restore($id, $newPass)) {
            $_SESSION['success'] = "User restored. Generic password is: $newPass. Please share this with the user.";
        }
        header("Location: " . url('admin/profile/index?status=trash'));
        exit;
    }

    public function profilePage()
    {
        $requestedId       = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $currentLoggedInId = (int) $this->sessionController->getUserId();

        // If no ID is passed, show the logged-in user's own profile
        $targetId = $requestedId ?: $currentLoggedInId;

        $user = $this->profileRepository->fetchById($targetId);

        if (! $user) {
            return $this->error404();
        }

        $error   = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $this->render('pages/profile', [
            'user'    => $user,
            'error'   => $error,
            'success' => $success,
            // Pass a flag to the view to hide/show edit buttons
            'canEdit' => ($this->sessionController->isAdmin() || $targetId === $currentLoggedInId),
        ]);
    }

    public function editProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUserId = (int) $this->sessionController->getUserId();
            $isAdmin       = $this->sessionController->isAdmin();
            $targetId      = ($isAdmin && isset($_POST['id'])) ? (int) $_POST['id'] : $currentUserId;

            $errors       = [];
            $profileImage = null;

            // 1. Basic Data Validation
            $username = trim($_POST['username'] ?? '');
            if (empty($username)) {
                $errors[] = "Username is required.";
            }

            // 2. Handle File Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize       = 2 * 1024 * 1024; // 2MB
                $uploadDir         = 'uploads/profiles/';

                $fileName      = $_FILES['image']['name'];
                $fileTmpName   = $_FILES['image']['tmp_name'];
                $fileSize      = $_FILES['image']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (! in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = "Invalid extension. Use JPG, PNG, or GIF.";
                }

                $finfo    = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($fileTmpName);
                if (! in_array($mimeType, $allowedMimeTypes)) {
                    $errors[] = "The file is not a valid image.";
                }

                if ($fileSize > $maxFileSize) {
                    $errors[] = "Image must be less than 2MB.";
                }

                if (empty($errors)) {
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $uniqueName  = "profile_" . $targetId . "_" . time() . '.' . $fileExtension;
                    $destination = $uploadDir . $uniqueName;

                    if (move_uploaded_file($fileTmpName, $destination)) {
                        $profileImage = $destination;
                    } else {
                        $errors[] = "Failed to save the image.";
                    }
                }
            }

            // 3. Process Result
            if (empty($errors)) {
                $socialLinks = [
                    ['platform' => 'facebook', 'url' => $_POST['facebook'] ?? ''],
                    ['platform' => 'twitter', 'url' => $_POST['twitter'] ?? ''],
                    ['platform' => 'github', 'url' => $_POST['github'] ?? ''],
                    ['platform' => 'instagram', 'url' => $_POST['instagram'] ?? ''],
                ];

                $data = [
                    'username'     => $username,
                    'location'     => trim($_POST['location'] ?? ''),
                    'bio'          => trim($_POST['bio'] ?? ''),
                    'social_links' => $socialLinks,
                ];

                // Only update the image if a new one was uploaded
                if ($profileImage) {
                    $data['image'] = $profileImage;
                }

                if ($this->profileRepository->updateProfile($targetId, $data)) {
                    $_SESSION['success'] = "Profile updated successfully!";
                    unset($_SESSION['old_post']); // Clear old data on success
                } else {
                    $_SESSION['error'] = "Database update failed.";
                }
                header("Location: " . url("admin/profile/view?id=$targetId"));
            } else {
                // Data persistence: Save the submitted data to session to show in form again
                $_SESSION['error']    = implode("<br>", $errors);
                $_SESSION['old_post'] = $_POST;
                header("Location: " . url("admin/profile/view?id=$targetId#settings"));
            }
            exit;
        }
    }

    public function deleteProfile()
    {
        // 1. Security Check: Ensure only admins can perform a permanent delete
        $this->ensureAdmin();

        // 2. Get the User ID from the request
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = "Invalid user ID provided.";
            header("Location: " . url('admin/profile/index?status=trash'));
            exit;
        }

        // 3. Security Safeguard: Prevent the admin from accidentally deleting themselves
        if ($id === (int) $this->sessionController->getUserId()) {
            $_SESSION['error'] = "Security Alert: You cannot delete your own account while logged in.";
            header("Location: " . url('admin/profile/index'));
            exit;
        }

        // 4. Execute permanent deletion via Repository
        if ($this->profileRepository->deletePermanently($id)) {
            $_SESSION['success'] = "The user account has been permanently removed from the system.";
        } else {
            $_SESSION['error'] = "Failed to delete the user. Please try again.";
        }

        // 5. Redirect back to the trash list
        header("Location: " . url('admin/profile/index?status=trash'));
        exit;
    }

    private function ensureAdmin()
    {
        if (! $this->sessionController->isAdmin()) {
            $_SESSION['error'] = "Access Denied: Only administrators can manage categories and tags.";
            header("Location: " . url('admin/dashboard'));
            exit;
        }
    }
}

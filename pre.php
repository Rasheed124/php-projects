    public function editPost()
    {

        $id = $_GET['id'] ?? null;
        if (! $id) {
            header('Location: ' . url('/admin/posts'));
            exit;
        }

        $post = $this->postsRepository->getPostById((int) $id);
        if (! $post) {
            die("Post not found.");
        }

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
                $errors[] = "No categories found. Please <a href='create-category.php'>create a category</a> first.";
            }

            $slug = isset($_POST['slug']) && ! empty($_POST['slug']) ? trim($_POST['slug']) : null;
            if (empty($slug)) {
                $slug = $this->generateSlug($title);
            }

            if ($this->postsRepository->slugExistsExcluding($slug, (int) $id)) {
                $errors[] = "The slug '{$slug}' is already in use by another post.";
            }

            $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];

            $status = isset($_POST['status']) ? $_POST['status'] : 'draft';

            // Handle file upload for thumbnail
            $thumbnail = $post['thumbnail'];

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
                $updateData = [
                    'title'       => $title,
                    'content'     => $content,
                    'category_id' => $category,
                    'slug'        => $slug,
                    'status'      => $status,
                    'thumbnail'   => ($thumbnail !== $post['thumbnail']) ? $thumbnail : null,
                ];
                $isCreated = $this->postsRepository->updatePost((int) $id, $updateData, $selectedTags);
                if ($isCreated) {
                    header('Location: ' . url('/admin/posts'));
                    exit;
                } else {
                    $errors[] = "Failed to create the post.";
                }
            }
        }

        $this->render('pages/edit-post', ['errors' => $errors, 'post' => $post, 'categories' => $categories, 'tags' => $tags, 'selectedTags' => $selectedTags]);
    }






    <div class="table-links">
                                        <?php if ($currentStatus === 'trash'): ?>
                                            <a href="<?php echo url('admin/posts/restore') ?>?id=<?php echo $post['id']; ?>">Restore</a>
                                            <div class="bullet"></div>
                                            <a href="<?php echo url('admin/posts/delete') ?>?id=<?php echo $post['id']; ?>"
                                            class="text-danger"
                                            onclick="return confirm('Permanently delete this post?')">Delete Permanently</a>
                                        <?php else: ?>
                                            <a href="#">View</a>
                                            <div class="bullet"></div>
                                            <a href="<?php echo url('admin/posts/edit') ?>?id=<?php echo $post['id']; ?>">Edit</a>
                                            <div class="bullet"></div>
                                            <a href="<?php echo url('admin/posts/trash') ?>?id=<?php echo $post['id']; ?>"
                                            class="text-danger">Trash</a>
                                        <?php endif; ?>
                                    </div>
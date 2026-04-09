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










        <section class="section">
            <div class="section-body">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card">
                <form action="<?php echo url('admin/categories/save'); ?>" method="POST">
                    <div class="card-header">
                        <h4 id="form-title">Create Category</h4>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id" id="category-id" value="0">
                        
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="name" id="category-name" class="form-control" required>
                            <small class="form-text text-muted">The name is how it appears on your site.</small>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-secondary" onclick="resetCategoryForm()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Save Category</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>All Categories</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cat->name); ?></td>
                                        <td><?php echo htmlspecialchars($cat->slug); ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                                    onclick="editCategory(<?php echo $cat->id; ?>, '<?php echo addslashes($cat->name); ?>')">
                                                Edit
                                            </button>
                                            <a href="<?php echo url('admin/categories/delete'); ?>?id=<?php echo $cat->id; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this category?')">
                                               Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="3" class="text-center">No categories found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        </section>
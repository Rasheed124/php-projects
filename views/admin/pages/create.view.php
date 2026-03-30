<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';
?>
  <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Write Your Post</h4>
                  </div>

                  <!-- ============================ FORM AREA -->
                   <form action="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'create']); ?>" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- Title Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="title" class="form-control" value="<?php echo isset($_POST['title']) ? e($_POST['title']) : ''; ?>" required>
                                </div>
                            </div>

                            <!-- Slug Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Slug</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="slug" class="form-control" value="<?php echo isset($_POST['slug']) ? e($_POST['slug']) : ''; ?>" >
                                    <small>Leave empty to auto-generate from the title.</small>
                                </div>
                            </div>

                            <!-- Category Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="category" class="form-control selectric" required>
                                        <option value="">Select a category</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo e($category['id']); ?>" <?php echo(isset($_POST['category']) && $_POST['category'] == $category['id']) ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="0" disabled>No categories available</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (empty($categories)): ?>
                                        <small class="text-danger">No categories found. Please <a href="create-category.php">create a category</a> first.</small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Content Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="content" class="summernote-simple" required><?php echo isset($_POST['content']) ? e($_POST['content']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Thumbnail Upload -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <!-- Status Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="status" class="form-control selectric">
                                        <option value="published" <?php echo(isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ''; ?>>Publish</option>
                                        <option value="draft" <?php echo(isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                        <option value="pending" <?php echo(isset($_POST['status']) && $_POST['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group row mb-4">
                                <div class="col-sm-12 col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-primary">Create Post</button>
                                </div>
                            </div>
                        </div>
                   </form>

                </div>
              </div>
            </div>
          </div>
        </section>
     <?php
         require __DIR__ . '/../../../views/admin/layouts/support-layouts/settingSiderbar.php';
     ?>
  </div>
<?php
require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php';
?>
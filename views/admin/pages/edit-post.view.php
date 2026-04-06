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
                    <h4>Edit the Post</h4>
                  </div>

                    <!-- ============================ FORM AREA -->
                        <form id="editForm" action="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'edit', 'post_id' => $post->post_id]); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <!-- Error Messages -->
                                <?php if (! empty($errors)): ?>
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
                                        <input type="text" name="title" class="form-control" value="<?php echo isset($_POST['title']) ? e($_POST['title']) : e($post->title); ?>" required>
                                    </div>
                                </div>

                                <!-- Slug Field -->
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Slug</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="slug" class="form-control" value="<?php echo isset($_POST['slug']) ? e($_POST['slug']) : e($post->slug); ?>">
                                        <small>Leave empty to auto-generate from the title.</small>
                                    </div>
                                </div>

                                <!-- Category Field -->
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="category" class="form-control selectric" required>
                                            <option value="">Select a category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo e($category['category_id']); ?>" <?php echo($category['category_id'] == $post->category_id) ? 'selected' : ''; ?>>
                                                    <?php echo e($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tags -->
                                     <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tags</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="tags[]" id="tag-select" class="form-control selectric" multiple required <?php if (empty($tags)): ?> disabled <?php else: ?> required <?php endif?>>
                                                <option value="">Select tags</option>
                                                <?php if (! empty($tags)): ?>
                                                    <?php foreach ($tags as $tag): ?>
                                                        <option value="<?php echo e($tag['tag_id']); ?>" <?php echo(isset($_POST['tags']) && in_array($tag['tag_id'], $_POST['tags']) ? 'selected' : ''); ?>>
                                                            <?php echo e($tag['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="0" disabled>No tags available</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if (empty($tags)): ?>
                                                <small class="text-danger">No tags found. Please <a href="create-tag.php">create a tag</a> first.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>





                                <!-- Content Field -->
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="content" class="summernote-simple" required><?php echo isset($_POST['content']) ? e($_POST['content']) : e($post->content); ?></textarea>
                                    </div>
                                </div>

                                <!-- Thumbnail Upload -->
                          <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                                <div class="col-sm-12 col-md-7">
                                    <!-- Image upload input -->
                                    <input type="file" name="image" class="form-control" accept="image/*">

                                    <?php if (! empty($_FILES['image']['name'])): ?>
                                        <br><img src="<?php echo e($_FILES['image']['tmp_name']); ?>" alt="New Thumbnail" width="100">
                                    <?php else: ?>
                                        <br><img src="<?php echo e($post->thumbnail); ?>" alt="Current Thumbnail" width="100">
                                    <?php endif?>

                                </div>
                            </div>

                                <!-- Status Field -->
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="status" class="form-control selectric">
                                            <option value="published" <?php echo(isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ($post->status == 'published' ? 'selected' : ''); ?>>Publish</option>
                                            <option value="draft" <?php echo(isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : ($post->status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 col-md-7 offset-md-3">
                                        <button type="submit" class="btn btn-primary">Update Post</button>
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
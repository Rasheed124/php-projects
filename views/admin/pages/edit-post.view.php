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
                            <h4>Edit Post: <?php echo e($post['title']); ?></h4>
                        </div>

                        <form id="editForm" action="<?php echo url('/admin/posts/edit') ?>?id=<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
                            <?php $adminSupport->csrfField(); ?>
                            <div class="card-body">
                                <?php if (! empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                            <?php foreach ($errors as $error): ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" id="post-create-title" name="title" class="form-control"
                                               value="<?php echo e($_POST['title'] ?? $post['title']); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Slug</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" id="post-create-slug"    name="slug" class="form-control"
                                               value="<?php echo e($_POST['slug'] ?? $post['slug']); ?>">
                                        <small>Modify only if you want to change the URL of this post.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="category" class="form-control selectric" <?php echo empty($categories) ? 'disabled' : 'required'; ?>>
                                            <option value="">Select a category</option>
                                            <?php if (! empty($categories)): ?>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?php echo e($cat->id); ?>"
                                                        <?php echo(($_POST['category'] ?? $post['category_id']) == $cat->id) ? 'selected' : ''; ?>>
                                                        <?php echo e($cat->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4" id="post_tags_edit">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tags</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select     name="tags[]"
            id="post-create-tag-select"
            class="form-control js-choice"
            multiple   class="form-control js-choice" multiple <?php echo empty($tags) ? 'disabled' : 'required'; ?>>
                                            <?php if (! empty($tags)): ?>
                                                <?php foreach ($tags as $tag): ?>
                                                    <option value="<?php echo e($tag->id); ?>"
                                                        <?php echo(in_array($tag->id, $selectedTags)) ? 'selected' : ''; ?>>
                                                        <?php echo e($tag->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="content" class="summernote-simple " id="post_content_editor" style="width: 100%;" required><?php echo e($_POST['content'] ?? $post['content']); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                                    <div class="col-sm-12 col-md-7">
                                        <?php if (! empty($post['thumbnail'])): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo url($post['thumbnail']); ?>" alt="Current Thumbnail" style="max-width: 150px; border-radius: 5px;">
                                                <p><small>Current thumbnail</small></p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <small>Leave empty to keep current thumbnail.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select name="status" class="form-control selectric">
                                            <?php $currentStatus = $_POST['status'] ?? $post['status']; ?>
                                            <option value="published" <?php echo($currentStatus === 'published') ? 'selected' : ''; ?>>Publish</option>
                                            <option value="draft" <?php echo($currentStatus === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 col-md-7 offset-md-3">
                                        <button type="submit" class="btn btn-success">Update Post</button>
                                        <a href="<?php echo url('/admin/posts'); ?>" class="btn btn-secondary">Cancel</a>
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
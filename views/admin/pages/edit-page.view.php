<?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Page: <?php echo e($page->title); ?></h4>
                        </div>

                        <form action="<?php echo url('/admin/pages/edit') ?>?id=<?php echo $page->id; ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul><?php foreach ($errors as $error): ?><li><?php echo e($error); ?></li><?php endforeach; ?></ul>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="title" class="form-control" 
                                               value="<?php echo e($_POST['title'] ?? $page->title); ?>" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="content" class="summernote-simple" style="width: 100%;" required><?php echo e($_POST['content'] ?? $page->content); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                                    <div class="col-sm-12 col-md-7">
                                        <?php if(!empty($page->thumbnail)): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo url($page->thumbnail); ?>" width="150" class="img-thumbnail">
                                                <p class="small text-muted">Current Thumbnail</p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <?php $currentStatus = $_POST['status'] ?? $page->status; ?>
                                        <select name="status" class="form-control selectric">
                                            <option value="published" <?php echo ($currentStatus == 'published') ? 'selected' : ''; ?>>Published</option>
                                            <option value="draft" <?php echo ($currentStatus == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 col-md-7 offset-md-3">
                                        <button type="submit" class="btn btn-primary">Update Page</button>
                                        <a href="<?php echo url('admin/pages/index'); ?>" class="btn btn-light">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/settingSiderbar.php'; ?>
</div>

<?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php'; ?>
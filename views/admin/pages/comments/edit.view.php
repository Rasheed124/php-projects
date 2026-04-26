<?php require __DIR__ . '/../../../../views/admin/layouts/support-layouts/header.php'; ?>


<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="<?php echo url('admin/comments/index'); ?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Comment & Response</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="<?php echo url('admin/comments/update'); ?>?id=<?php echo $comment['id']; ?>" method="post">
                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Author</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" class="form-control" value="<?php echo e($comment['guest_name'] ?? $comment['auth_name']); ?>" disabled>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Original Comment</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="comment" class="form-control" style="height: 150px;" required><?php echo e($comment['comment']); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Admin/Owner Response</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="reply" class="form-control" style="height: 150px;" placeholder="Write your response here..."><?php echo e($comment['reply'] ?? ''); ?></textarea>
                                        <small class="form-text text-muted">This response will appear directly under the comment.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../../../../views/admin/layouts/support-layouts/footer.php'; ?>

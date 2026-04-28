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
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?php echo e($comment['auth_name'] ?? $comment['author_name']); ?>" disabled>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <?php echo $comment['user_id'] ? 'Registered User' : 'Guest (' . e($comment['author_email']) . ')'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Current Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <span class="badge badge-<?php echo ($comment['status'] === 'approved') ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($comment['status']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Comment Content</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="comment" class="form-control" style="height: 150px;" required><?php echo e($comment['content']); ?></textarea>
                                    </div>
                                </div>

                                <?php if(is_null($comment['parent_id'])): ?>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Your Response</label>
                                    <div class="col-sm-12 col-md-7">
                                        <textarea name="reply" class="form-control" style="height: 150px;" placeholder="Write your response here..."><?php echo e($comment['reply'] ?? ''); ?></textarea>
                                        <small class="form-text text-muted">This creates/updates a nested reply in the database.</small>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="alert alert-light border">
                                            <i class="fas fa-info-circle"></i> This is a reply. You cannot add a nested response to a reply.
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <a href="<?php echo url('admin/comments/index'); ?>" class="btn btn-secondary">Cancel</a>
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
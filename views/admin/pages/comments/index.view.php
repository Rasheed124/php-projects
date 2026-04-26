<?php require __DIR__ . '/../../../../views/admin/layouts/support-layouts/header.php'; ?>


<?php 
// Group comments by post_id in PHP
$groupedComments = [];
foreach ($comments as $c) {
    $groupedComments[$c['post_id']]['title'] = $c['post_title'];
    $groupedComments[$c['post_id']]['slug'] = $c['post_slug'];
    $groupedComments[$c['post_id']]['items'][] = $c;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Comment Moderation</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <ul class="nav nav-pills">
                                <?php foreach (['all' => 'All', '1' => 'Approved', '0' => 'Pending'] as $key => $label): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($currentStatus == $key) ? 'active' : ''; ?>" href="?status=<?php echo $key; ?>">
                                        <?php echo $label; ?> <span class="badge"><?php echo $counts[$key] ?? 0; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($groupedComments)): ?>
                <div class="card"><div class="card-body text-center">No comments to display.</div></div>
            <?php else: ?>
                <?php foreach ($groupedComments as $postId => $group): ?>
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Post: <a href="<?php echo url('post/'.$group['slug']); ?>" target="_blank"><?php echo e($group['title']); ?></a></h4>
                        <div class="card-header-action">
                            <span class="badge badge-info"><?php echo count($group['items']); ?> Comments</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 200px;">Author</th>
                                        <th>Comment Snippet</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['items'] as $c): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($c['guest_name'] ?? $c['auth_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo date('M d, H:i', strtotime($c['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <div style="font-size: 13px;"><?php echo e($c['comment']); ?></div>
                                            <?php if($c['parent_id']): ?>
                                                <div class="mt-1"><span class="badge badge-light" style="font-size: 9px;">REPLY TO ID: #<?php echo $c['parent_id']; ?></span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="badge badge-<?php echo ($c['is_approved'] == 1) ? 'success' : 'warning'; ?>">
                                                <?php echo ($c['is_approved'] == 1) ? 'Approved' : 'Pending'; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo url('admin/comments/edit'); ?>?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit/Reply"><i class="fas fa-reply"></i></a>
                                                <?php if($c['is_approved'] == 0): ?>
                                                    <a href="<?php echo url('admin/comments/approve'); ?>?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                                <?php else: ?>
                                                    <a href="<?php echo url('admin/comments/reject'); ?>?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-times"></i></a>
                                                <?php endif; ?>
                                                <a href="<?php echo url('admin/comments/delete'); ?>?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../../../../views/admin/layouts/support-layouts/footer.php'; ?>

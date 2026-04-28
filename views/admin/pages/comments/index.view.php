<?php require __DIR__ . '/../../../../views/admin/layouts/support-layouts/header.php'; ?>

<?php 
// Advanced Grouping: Group by Post AND maintain hierarchy
$groupedComments = [];
foreach ($comments as $c) {
    $groupedComments[$c['post_id']]['title'] = $c['post_title'];
    $groupedComments[$c['post_id']]['slug'] = $c['post_slug'];
    $groupedComments[$c['post_id']]['items'][] = $c;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header"><h1>Comment Moderation</h1></div>

        <div class="section-body">
            <div class="row mb-4">
                <div class="col-12">
                    <ul class="nav nav-pills">
                        <?php foreach (['all' => 'All', 'approved' => 'Approved', 'pending' => 'Pending'] as $key => $label): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentStatus == $key) ? 'active' : ''; ?>" href="?status=<?php echo $key; ?>">
                                <?php echo $label; ?> 
                                <span class="badge badge-white ml-1"><?php echo $counts[$key] ?? 0; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <?php if (empty($groupedComments)): ?>
                <div class="card"><div class="card-body text-center">No comments found.</div></div>
            <?php else: ?>
                <?php foreach ($groupedComments as $postId => $group): ?>
                <div class="card shadow-sm border-left-primary">
                    <div class="card-header bg-light">
                        <h4>Post: <a href="<?php echo url('post/'.$group['slug']); ?>" target="_blank" class="text-primary"><?php echo e($group['title']); ?></a></h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Author</th>
                                        <th>Comment Message</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['items'] as $c): ?>
                                    <tr style="<?php echo $c['parent_id'] ? 'background-color: #fafafa;' : ''; ?>">
                                        <td style="padding-left: <?php echo $c['parent_id'] ? '40px' : '20px'; ?>;">
                                            <?php if($c['parent_id']): ?>
                                                <i class="fas fa-level-up-alt fa-rotate-90 text-muted mr-1"></i>
                                            <?php endif; ?>
                                            <strong><?php echo e($c['auth_name'] ?? $c['author_name']); ?></strong>
                                            <?php if($c['user_id']): ?> <span class="badge badge-secondary p-1" style="font-size: 8px;">User</span> <?php endif; ?>
                                            <br><small class="text-muted"><?php echo date('M d, Y H:i', strtotime($c['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 400px; font-size: 0.9rem;">
                                                <?php echo e($c['content']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo ($c['status'] === 'approved') ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($c['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo url('admin/comments/edit?id='.$c['id']); ?>" class="btn btn-sm btn-outline-info" title="Edit/Reply"><i class="fas fa-edit"></i></a>
                                                
                                                <?php if($c['status'] === 'pending'): ?>
                                                    <a href="<?php echo url('admin/comments/approve?id='.$c['id']); ?>" class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></a>
                                                <?php else: ?>
                                                    <a href="<?php echo url('admin/comments/reject?id='.$c['id']); ?>" class="btn btn-sm btn-warning" title="Move to Pending"><i class="fas fa-undo"></i></a>
                                                <?php endif; ?>
                                                
                                                <a href="<?php echo url('admin/comments/delete?id='.$c['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Permanent delete?')" title="Delete"><i class="fas fa-trash"></i></a>
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
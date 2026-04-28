<?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>All Pages</h1>
            <div class="section-header-button">
                <a href="<?php echo url('admin/pages/create'); ?>" class="btn btn-primary">Add New</a>
            </div>
        </div>

        <div class="section-body">
            <?php if (!empty($error) || !empty($success)): ?>
                <div class="alert alert-<?php echo !empty($error) ? 'danger' : 'success'; ?> alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <?php echo e($error ?? $success); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-body">
                            <ul class="nav nav-pills">
                                <?php 
                                $tabs = ['all' => 'All', 'published' => 'Published', 'draft' => 'Draft'];
                                foreach ($tabs as $key => $label): 
                                    $active = ($currentStatus === $key) ? 'active' : '';
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $active; ?>" href="<?php echo url('admin/pages/index'); ?>?status=<?php echo $key; ?>">
                                        <?php echo $label; ?> <span class="badge <?php echo $active ? 'badge-white' : 'badge-primary'; ?>"><?php echo $counts[$key]; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Manage <?php echo ucfirst($currentStatus); ?> Pages</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Thumbnail</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pages)): foreach ($pages as $p): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo !empty($p['thumbnail']) ? url($p['thumbnail']) : url('assets/img/news/img01.jpg'); ?>" width="50" class="img-fluid rounded" style="height:40px; object-fit:cover;">
                                            </td>
                                            <td>
                                                <strong><?php echo e($p['title']); ?></strong>
                                                <div class="table-links">
                                                    <a href="<?php echo url($p['slug']); ?>" target="_blank">View</a>
                                                    <div class="bullet"></div>
                                                    <a href="<?php echo url('admin/pages/edit'); ?>?id=<?php echo $p['id']; ?>">Edit</a>
                                                    <div class="bullet"></div>
                                                    <a href="<?php echo url('admin/pages/delete'); ?>?id=<?php echo $p['id']; ?>" class="text-danger" onclick="return confirm('Permanently delete this page?')">Delete</a>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="<?php echo asset('admin/img/user.png'); ?>" class="rounded-circle mr-1" width="20">
                                                <?php echo e($p['author_name'] ?? 'Admin'); ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
                                            <td>
                                                <div class="badge badge-<?php echo ($p['status'] === 'published') ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($p['status']); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center">No pages found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if($totalPages > $limit): ?>
                            <div class="float-right mt-3">
                                <nav>
                                    <ul class="pagination">
                                        <?php $pagesCount = ceil($totalPages / $limit); ?>
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?status=<?php echo $currentStatus; ?>&page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                                        </li>
                                        <?php for($i = 1; $i <= $pagesCount; $i++): ?>
                                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?status=<?php echo $currentStatus; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $pagesCount) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?status=<?php echo $currentStatus; ?>&page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php'; ?>
<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">

              <div class="row">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close"  data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <i class="fas fa-exclamation-triangle"></i> <?php echo e($error); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close"  data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-body">
                             <ul class="nav nav-pills">
                                <?php
                                $tabs = [
                                    'all'       => 'All',
                                    'published' => 'Published',
                                    'draft'     => 'Draft',
                                    'trash'     => 'Trash', // Added Trash Tab
                                ];
                                foreach ($tabs as $key => $label):
                                    $isActive = ($currentStatus === $key);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" 
                                    href="<?php echo url('admin/posts') ?>?status=<?php echo $key; ?>">
                                        <?php echo $label; ?> 
                                        <span class="badge <?php echo $isActive ? 'badge-white' : 'badge-primary'; ?>">
                                            <?php echo $counts[$key] ?? 0; ?>
                                        </span>
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
                            <h4><?php echo ucfirst($currentStatus); ?> Posts</h4>
                        </div>
                        <div class="card-body">
                               <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Thumbnail</th> <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Created At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($posts)): ?>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td>
                                        <?php if (! empty($post['thumbnail'])): ?>
                                            <img alt="image" src="<?php echo url($post['thumbnail']); ?>" class="img-fluid" width="50" style="border-radius: 4px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <img alt="placeholder" src="<?php echo url('assets/img/news/img01.jpg'); ?>" class="img-fluid" width="50" style="border-radius: 4px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>

                                   <td>
                                    <?php echo e($post['title']); ?>
                            
                                        <div class="table-links">
                                            <?php 
                                                 $isAdmin = $this->sessionController->isAdmin();
                                                 $isOwner = ((int)$post['user_id'] === (int)$this->sessionController->getUserID());
                                                $hasAccess = ($isAdmin || $isOwner);
                                            ?>

                                            <?php if ($currentStatus === 'trash'): ?>
                                                <?php if ($hasAccess): ?>
                                                    <a href="<?php echo url('admin/posts/restore') ?>?id=<?php echo $post['id']; ?>">Restore</a>
                                                    <div class="bullet"></div>
                                                    <a href="<?php echo url('admin/posts/delete') ?>?id=<?php echo $post['id']; ?>" 
                                                    class="text-danger" 
                                                    onclick="return confirm('Permanently delete this post?')">Delete Permanently</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No actions available</span>
                                                <?php endif; ?>

                                            <?php else: ?>
                                                <a href="<?php echo url('post/'.$post['slug']); ?>">View</a>
                                                
                                                <?php if ($hasAccess): ?>
                                                    <div class="bullet"></div>
                                                    <a href="<?php echo url('admin/posts/edit') ?>?id=<?php echo $post['id']; ?>">Edit</a>
                                                    <div class="bullet"></div>
                                                    <a href="<?php echo url('admin/posts/trash') ?>?id=<?php echo $post['id']; ?>" 
                                                    class="text-danger">Trash</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                     </td>

                                   <td>
                                        <img alt="image" src="<?php echo asset('admin/img/user.png'); ?>" class="rounded-circle" width="25">
                                        <span class="d-inline-block ml-1">
                                            <?php echo e($post['author_name'] ?? 'Unknown'); ?>
                                        </span>
                                    </td>

                                    <td><?php echo e($post['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <?php if ($currentStatus === 'trash'): ?>
                                            <div class="badge badge-danger">
                                                <i class="fas fa-trash"></i> Trashed
                                            </div>
                                        <?php else: ?>
                                            <div class="badge badge-<?php echo ($post['status'] === 'published') ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($post['status']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No posts found in this category.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                            <div class="float-right">
                                <nav>
                                    <ul class="pagination">
                                        <?php
                                            $totalPages = ceil($totalPosts / $limit);

                                            // Previous Link
                                            $prevDisabled = ($page <= 1) ? 'disabled' : '';
                                        ?>
                                        <li class="page-item <?php echo $prevDisabled; ?>">
                                            <a class="page-link" href="<?php echo url('admin/posts') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $page - 1; ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo($page == $i) ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?php echo url('admin/posts') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php
                                            // Next Link
                                            $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
                                        ?>
                                        <li class="page-item <?php echo $nextDisabled; ?>">
                                            <a class="page-link" href="<?php echo url('admin/posts') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $page + 1; ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php';
?>
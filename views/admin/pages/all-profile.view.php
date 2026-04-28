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
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <i class="fas fa-check-circle"></i> 
                                    <strong>Action Successful:</strong> <?php echo e($success); ?>
                                    <?php if(str_contains($success, 'password is:')): ?>
                                        <br><small>Please copy this password now. It will not be shown again for security reasons.</small>
                                    <?php endif; ?>
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
                                    'trash'     => 'Trash', // Added Trash Tab
                                ];
                                foreach ($tabs as $key => $label):
                                    $isActive = ($currentStatus === $key);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" 
                                    href="<?php echo url('admin/profile') ?>?status=<?php echo $key; ?>">
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
                            <h4><?php echo ucfirst($currentStatus); ?> Users</h4>
                        </div>
                        <div class="card-body">
                               <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User Image</th> 
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>User Role</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo url($u['profile_image']) ?? asset('admin/img/user.png'); ?>" class="rounded-circle" width="35">
                                        
                                    </td>
                                   
                                    <td>
                                        <strong><?php echo e($u['username']); ?></strong>
                                        <div class="table-links">
    <a href="<?php echo url('admin/profile/view?id='.$u['id']); ?>">View</a>
    
    <?php 
    $isSelf = ((int)$u['id'] === (int)$this->sessionController->getUserId());
    
    if ($this->sessionController->isAdmin()): ?>
        <div class="bullet"></div>
        
        <?php if ($currentStatus === 'trash'): ?>
            <a href="<?php echo url('admin/profile/restore?id='.$u['id']); ?>" class="text-success">Restore</a>
            <?php if (!$isSelf): ?>
                <div class="bullet"></div>
               <form action="<?php echo url('admin/profile/delete'); ?>?id=<?php echo $u['id']; ?>" 
              method="POST" 
              style="display:inline;" 
              id="delete-form-<?php echo $u['id']; ?>">
            
            <?php echo $adminSupport->csrfField(); ?>
            
            <a href="javascript:void(0);" 
               class="text-danger" 
               onclick="if(confirm('Are you sure you want to delete this user forever? This cannot be undone.')) { document.getElementById('delete-form-<?php echo $u['id']; ?>').submit(); }">
               Delete
            </a>
        </form>
            <?php endif; ?>

        <?php else: ?>
            <a href="<?php echo url('admin/profile/view?id='.$u['id']).'#settings'; ?>">Edit</a>
            
            <?php if (!$isSelf): ?>
                <div class="bullet"></div>
                <a href="<?php echo url('admin/profile/trash?id='.$u['id']); ?>" class="text-danger" onclick="return confirm('Move to trash?')">Trash</a>
            <?php else: ?>
                <div class="bullet"></div>
                <span class="text-muted" title="You cannot trash yourself">Owner</span>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
                                    </td>
                                     <td><?php echo e($u['email']); ?></td>
                                    <td><?php echo e($u['role']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No Users Trashed</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                           <div class="float-right">
                                <nav>
                                    <ul class="pagination">
                                        <?php
                                            // Calculate total pages based on user count
                                            $totalPages = ceil($totalUsers / $limit);

                                            // Previous Link logic
                                            $prevDisabled = ($page <= 1) ? 'disabled' : '';
                                            $prevPage = ($page > 1) ? $page - 1 : 1;
                                        ?>
                                        
                                        <li class="page-item <?php echo $prevDisabled; ?>">
                                            <a class="page-link" href="<?php echo url('admin/profile/index') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $prevPage; ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?php echo url('admin/profile/index') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php
                                            // Next Link logic
                                            $nextDisabled = ($page >= $totalPages) ? 'disabled' : '';
                                            $nextPage = ($page < $totalPages) ? $page + 1 : $totalPages;
                                        ?>
                                        
                                        <li class="page-item <?php echo $nextDisabled; ?>">
                                            <a class="page-link" href="<?php echo url('admin/profile/index') ?>?status=<?php echo $currentStatus; ?>&page=<?php echo $nextPage; ?>">
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
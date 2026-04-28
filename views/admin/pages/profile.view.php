<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';

    $currentLoggedInId = (int) $this->sessionController->getUserId();
    $isAdmin           = $this->sessionController->isAdmin();
    $isOwner           = ($user->id == $currentLoggedInId);
    $canEdit           = ($isAdmin || $isOwner);

    $socials = $user->getSocialLinksArray();
    $getLink = function ($platform) use ($socials) {
    foreach ($socials as $s) {
        if ($s['platform'] === $platform) {
            return $s['url'];
        }

    }
    return '';
    };
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card author-box">
                        <div class="card-body">
                            <div class="author-box-center">
                                  
                                <img alt="image" src="<?php echo url($user->profile_image) ?? asset('admin/img/user.png'); ?>" style="width: 100px;  height: 100px;" class="rounded-circle author-box-picture">
                                <div class="author-box-name"><?php echo e($user->username); ?></div>
                            </div>
                            <div class="text-center">
                            <p>
                                <?php 
                                    $bio = $user->bio ?? 'No bio added yet.';
                                    echo e(mb_strimwidth($bio, 0, 100, "...")); 
                                ?>
                            </p>
                                <div class="mb-2 mt-3"><div class="text-small font-weight-bold">Follow On</div></div>
                                <a href="<?php echo e($getLink('facebook')); ?>" class="btn btn-social-icon mr-1 btn-facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="<?php echo e($getLink('twitter')); ?>" class="btn btn-social-icon mr-1 btn-twitter"><i class="fab fa-twitter"></i></a>
                                <a href="<?php echo e($getLink('github')); ?>" class="btn btn-social-icon mr-1 btn-github"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-8">
                    <div class="card">
                        <div class="padding-20">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab">About</a>
                                </li>
                                <?php if ($canEdit): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab">Settings</a>
                                </li>
                                <?php endif; ?>
                            </ul>

                            <div class="tab-content tab-bordered" id="myTab3Content">
                                <div class="tab-pane fade show active" id="about" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-3 col-6 b-r"><strong>Full Name</strong><br><p><?php echo e($user->username); ?></p></div>
                                        <div class="col-md-3 col-6 b-r"><strong>Email</strong><br><p><?php echo e($user->email); ?></p></div>
                                        <div class="col-md-3 col-6"><strong>Location</strong><br><p><?php echo e($user->location ?? 'Not set'); ?></p></div>
                                    </div>
                                    <p class="m-t-30"><?php echo e($user->bio); ?></p>
                                </div>

                                <?php if ($canEdit): ?>
                                <div class="tab-pane fade" id="settings" role="tabpanel">
                                    <form action="<?php echo url('admin/profile/edit'); ?>" method="post" enctype="multipart/form-data" class="needs-validation">
                                        <?php $adminSupport->csrfField(); ?>
                                        <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                                        <div class="card-header"><h4>Edit Profile</h4></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>User Name</label>
                                                    <input type="text" name="username" class="form-control" value="<?php echo e($user->username); ?>" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Location</label>
                                                    <input type="text" name="location" class="form-control" value="<?php echo e($user->location); ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label>Bio</label>
                                                    <textarea name="bio" class="form-control summernote-simple"><?php echo e($user->bio); ?></textarea>
                                                </div>
                                            </div>
                                                 <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="col-form-label ">Upload Profile Image</label>
                                                    <div class="col-sm-12 col-md-7">
                                                        <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6"><label>Facebook URL</label><input type="text" name="facebook" class="form-control" value="<?php echo e($getLink('facebook')); ?>"></div>
                                                <div class="form-group col-md-6"><label>Twitter URL</label><input type="text" name="twitter" class="form-control" value="<?php echo e($getLink('twitter')); ?>"></div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php'; ?>

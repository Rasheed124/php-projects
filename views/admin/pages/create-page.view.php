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
                    <h4>Create Your Page</h4>
                  </div>

                  <!-- ============================ FORM AREA -->
                   <form id="createForm" action="<?php echo url('/admin/pages/create') ?>" method="POST" enctype="multipart/form-data">
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
                                    <input type="text" id="post-create-title" name="title" class="form-control" value="<?php echo isset($_POST['title']) ? e($_POST['title']) : ''; ?>" >
                                </div>
                            </div>



                            <!-- Content Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="content" class="summernote-simple" style="width: 100%;" ><?php echo isset($_POST['content']) ? e($_POST['content']) : ''; ?></textarea>
                                </div>
                            </div>


                            <!-- Thumbnail Upload -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                            </div>

                                    <!-- Status Field -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="status" class="form-control selectric">
                                        <option value="published" <?php echo(isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : ''; ?>>Publish</option>
                                        <option value="draft" <?php echo(isset($_POST['status']) && $_POST['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                            </div>



                            <!-- Submit Button -->
                           <div class="form-group row mb-4">
                            <div class="col-sm-12 col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-primary">Create Page</button>
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
     <?php
         require __DIR__ . '/../../../views/admin/layouts/support-layouts/settingSiderbar.php';
     ?>
  </div>
<?php
require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php';
?>
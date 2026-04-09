<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Taxonomy Management</h1>
        </div>

        <div class="section-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <form action="<?php echo url('admin/taxonomy/categories/save'); ?>" method="POST">
                            <div class="card-header">
                                <h4 id="cat-form-title">Create Category</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" id="cat-id" value="0">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" id="cat-name" class="form-control" required>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($cat->name); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="editCategory(<?php echo $cat->id; ?>, '<?php echo addslashes($cat->name); ?>')"><i class="fas fa-edit"></i></button>
                                                        <a href="<?php echo url('admin/taxonomy/categories/delete'); ?>?id=<?php echo $cat->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-light" onclick="resetCatForm()">Reset</button>
                                <button type="submit" class="btn btn-primary" id="cat-submit-btn">Save Category</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <form action="<?php echo url('admin/taxonomy/tags/save'); ?>" method="POST">
                            <div class="card-header">
                                <h4 id="tag-form-title">Create Tag</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" id="tag-id" value="0">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" id="tag-name" class="form-control" required>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($tags)): foreach ($tags as $tag): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($tag->name); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="editTag(<?php echo $tag->id; ?>, '<?php echo addslashes($tag->name); ?>')"><i class="fas fa-edit"></i></button>
                                                        <a href="<?php echo url('admin/taxonomy/tags/delete'); ?>?id=<?php echo $tag->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete tag?')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-light" onclick="resetTagForm()">Reset</button>
                                <button type="submit" class="btn btn-primary" id="tag-submit-btn">Save Tag</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/settingSiderbar.php'; ?>
</div>

<script>
// CATEGORY JS
function editCategory(id, name) {
    document.getElementById('cat-form-title').innerText = 'Edit Category: ' + name;
    document.getElementById('cat-id').value = id;
    document.getElementById('cat-name').value = name;
    document.getElementById('cat-submit-btn').innerText = 'Update Category';
}
function resetCatForm() {
    document.getElementById('cat-form-title').innerText = 'Create Category';
    document.getElementById('cat-id').value = '0';
    document.getElementById('cat-name').value = '';
    document.getElementById('cat-submit-btn').innerText = 'Save Category';
}

// TAG JS
function editTag(id, name) {
    document.getElementById('tag-form-title').innerText = 'Edit Tag: ' + name;
    document.getElementById('tag-id').value = id;
    document.getElementById('tag-name').value = name;
    document.getElementById('tag-submit-btn').innerText = 'Update Tag';
}
function resetTagForm() {
    document.getElementById('tag-form-title').innerText = 'Create Tag';
    document.getElementById('tag-id').value = '0';
    document.getElementById('tag-name').value = '';
    document.getElementById('tag-submit-btn').innerText = 'Save Tag';
}
</script>

<?php
require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php';
?>
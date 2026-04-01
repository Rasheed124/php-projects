<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';
?>
  <div class="main-content">
     <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card mb-0">
                  <div class="card-body">
                  <ul class="nav nav-pills" id="postTabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="pill" href="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'posts']); ?>">All <span class="badge badge-white">10</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="draft-tab" data-toggle="pill" href="index.php?<?php echo http_build_query(['route' => 'admin/pages', 'page' => 'drafts']); ?>">Draft <span class="badge badge-primary">2</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pending-tab" data-toggle="pill" href="#pending-posts">Pending <span class="badge badge-primary">3</span></a>
                        </li>
                     
                    </ul>

                       
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-12">

             <div class="tab-content mt-4" id="postTabsContent">
                  <!-- All Posts -->
                  <div class="tab-pane fade show active" id="all-posts">
                      <div class="card">
                          <div class="card-header">
                              <h4>All Posts</h4>
                          </div>
                          <div class="card-body">
                              <div class="float-left">
                                  <select class="form-control selectric">
                                      <option>Action For Selected</option>
                                      <option>Move to Draft</option>
                                      <option>Move to Pending</option>
                                      <option>Delete Permanently</option>
                                  </select>
                              </div>
                              <div class="float-right">
                                  <form>
                                      <div class="input-group">
                                          <input type="text" class="form-control" placeholder="Search">
                                          <div class="input-group-append">
                                              <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                              <div class="clearfix mb-3"></div>

                              <div class="table-responsive">
                                  <table class="table table-striped">
                                      <tr>
                                          <th class="pt-2">
                                              <div class="custom-checkbox custom-checkbox-table custom-control">
                                                  <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                        class="custom-control-input" id="checkbox-all">
                                                  <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                              </div>
                                          </th>
                                          <th>Author</th>
                                          <th>Title</th>
                                          <th>Category</th>
                                          <th>Created At</th>
                                          <th>Published At</th>
                                          <th>Status</th>
                                          <th>Thumbnail</th>
                                      </tr>

                                      <?php foreach ($allPosts as $post): ?>
                                          <tr>
                                              <td>
                                                  <div class="custom-checkbox custom-control">
                                                      <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                            id="checkbox-<?php echo $post['post_id']; ?>">
                                                      <label for="checkbox-<?php echo $post['post_id']; ?>" class="custom-control-label">&nbsp;</label>
                                                  </div>
                                              </td>
                                              <td>
                                                  <!-- <a href="#"> -->
                                                      <span class="d-inline-block ml-1"><?php echo $post['author_name']; ?></span>
                                                  <!-- </a> -->
                                              </td>
                                              <td><?php echo $post['title']; ?></td>
                                              <td><a href="#"><?php echo $post['category_name']; ?></a></td>
                                              <td><?php echo $post['created_at']; ?></td>
                                              <td><?php echo $post['published_at']; ?></td>
                                              <td>
                                                  <div class="badge badge-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                      <?php echo ucfirst($post['status']); ?>
                                                  </div>
                                              </td>
                                              <td><a href="#"><img alt="image" src="<?php echo $post['thumbnail']; ?>"
                                                                  class="rounded-circle" width="35" title=""></a></td>
                                          </tr>
                                      <?php endforeach; ?>
                                  </table>
                              </div>

                              <div class="float-right">
                                  <nav>
                                      <ul class="pagination">
                                          <li class="page-item disabled">
                                              <a class="page-link" href="#" aria-label="Previous">
                                                  <span aria-hidden="true">&laquo;</span>
                                                  <span class="sr-only">Previous</span>
                                              </a>
                                          </li>
                                          <li class="page-item active">
                                              <a class="page-link" href="#">1</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">2</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">3</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#" aria-label="Next">
                                                  <span aria-hidden="true">&raquo;</span>
                                                  <span class="sr-only">Next</span>
                                              </a>
                                          </li>
                                      </ul>
                                  </nav>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Draft Posts -->
                  <div class="tab-pane fade" id="draft-posts">
                      <div class="card">
                          <div class="card-header">
                              <h4>Draft Posts</h4>
                          </div>
                          <div class="card-body">
                              <div class="float-left">
                                  <select class="form-control selectric">
                                      <option>Action For Selected</option>
                                      <option>Move to Draft</option>
                                      <option>Move to Pending</option>
                                      <option>Delete Permanently</option>
                                  </select>
                              </div>
                              <div class="float-right">
                                  <form>
                                      <div class="input-group">
                                          <input type="text" class="form-control" placeholder="Search">
                                          <div class="input-group-append">
                                              <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                              <div class="clearfix mb-3"></div>

                              <div class="table-responsive">
                                  <table class="table table-striped">
                                      <tr>
                                          <th class="pt-2">
                                              <div class="custom-checkbox custom-checkbox-table custom-control">
                                                  <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                        class="custom-control-input" id="checkbox-all">
                                                  <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                              </div>
                                          </th>
                                          <th>Author</th>
                                          <th>Title</th>
                                          <th>Category</th>
                                          <th>Created At</th>
                                          <th>Published At</th>
                                          <th>Status</th>
                                          <th>Thumbnail</th>
                                      </tr>

                                      <?php foreach ($draftPosts as $post): ?>
                                          <tr>
                                              <td>
                                                  <div class="custom-checkbox custom-control">
                                                      <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                            id="checkbox-<?php echo $post['id']; ?>">
                                                      <label for="checkbox-<?php echo $post['id']; ?>" class="custom-control-label">&nbsp;</label>
                                                  </div>
                                              </td>
                                              <td>
                                                  <a href="#">
                                                      <span class="d-inline-block ml-1"><?php echo $post['author_name']; ?></span>
                                                  </a>
                                              </td>
                                              <td><?php echo $post['title']; ?></td>
                                              <td><a href="#"><?php echo $post['category_name']; ?></a></td>
                                              <td><?php echo $post['created_at']; ?></td>
                                              <td><?php echo $post['published_at']; ?></td>
                                              <td>
                                                  <div class="badge badge-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                      <?php echo ucfirst($post['status']); ?>
                                                  </div>
                                              </td>
                                              <td><a href="#"><img alt="image" src="<?php echo $post['thumbnail']; ?>"
                                                                  class="rounded-circle" width="35" title=""></a></td>
                                          </tr>
                                      <?php endforeach; ?>
                                  </table>
                              </div>

                              <div class="float-right">
                                  <nav>
                                      <ul class="pagination">
                                          <li class="page-item disabled">
                                              <a class="page-link" href="#" aria-label="Previous">
                                                  <span aria-hidden="true">&laquo;</span>
                                                  <span class="sr-only">Previous</span>
                                              </a>
                                          </li>
                                          <li class="page-item active">
                                              <a class="page-link" href="#">1</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">2</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">3</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#" aria-label="Next">
                                                  <span aria-hidden="true">&raquo;</span>
                                                  <span class="sr-only">Next</span>
                                              </a>
                                          </li>
                                      </ul>
                                  </nav>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Pending Posts -->
                  <div class="tab-pane fade" id="pending-posts">
                        <div class="card">
                          <div class="card-header">
                              <h4>Pending Posts</h4>
                          </div>
                          <div class="card-body">
                              <div class="float-left">
                                  <select class="form-control selectric">
                                      <option>Action For Selected</option>
                                      <option>Move to Draft</option>
                                      <option>Move to Pending</option>
                                      <option>Delete Permanently</option>
                                  </select>
                              </div>
                              <div class="float-right">
                                  <form>
                                      <div class="input-group">
                                          <input type="text" class="form-control" placeholder="Search">
                                          <div class="input-group-append">
                                              <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                              <div class="clearfix mb-3"></div>

                              <div class="table-responsive">
                                  <table class="table table-striped">
                                      <tr>
                                          <th class="pt-2">
                                              <div class="custom-checkbox custom-checkbox-table custom-control">
                                                  <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                        class="custom-control-input" id="checkbox-all">
                                                  <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                              </div>
                                          </th>
                                          <th>Author</th>
                                          <th>Title</th>
                                          <th>Category</th>
                                          <th>Created At</th>
                                          <th>Published At</th>
                                          <th>Status</th>
                                          <th>Thumbnail</th>
                                      </tr>

                                      <?php foreach ($pendingPosts as $post): ?>
                                          <tr>
                                              <td>
                                                  <div class="custom-checkbox custom-control">
                                                      <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                            id="checkbox-<?php echo $post['id']; ?>">
                                                      <label for="checkbox-<?php echo $post['id']; ?>" class="custom-control-label">&nbsp;</label>
                                                  </div>
                                              </td>
                                              <td>
                                                  <a href="#">
                                                      <span class="d-inline-block ml-1"><?php echo $post['author_name']; ?></span>
                                                  </a>
                                              </td>
                                              <td><?php echo $post['title']; ?></td>
                                              <td><a href="#"><?php echo $post['category_name']; ?></a></td>
                                              <td><?php echo $post['created_at']; ?></td>
                                              <td><?php echo $post['published_at']; ?></td>
                                              <td>
                                                  <div class="badge badge-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                      <?php echo ucfirst($post['status']); ?>
                                                  </div>
                                              </td>
                                              <td><a href="#"><img alt="image" src="<?php echo $post['thumbnail']; ?>"
                                                                  class="rounded-circle" width="35" title=""></a></td>
                                          </tr>
                                      <?php endforeach; ?>
                                  </table>
                              </div>

                              <div class="float-right">
                                  <nav>
                                      <ul class="pagination">
                                          <li class="page-item disabled">
                                              <a class="page-link" href="#" aria-label="Previous">
                                                  <span aria-hidden="true">&laquo;</span>
                                                  <span class="sr-only">Previous</span>
                                              </a>
                                          </li>
                                          <li class="page-item active">
                                              <a class="page-link" href="#">1</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">2</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#">3</a>
                                          </li>
                                          <li class="page-item">
                                              <a class="page-link" href="#" aria-label="Next">
                                                  <span aria-hidden="true">&raquo;</span>
                                                  <span class="sr-only">Next</span>
                                              </a>
                                          </li>
                                      </ul>
                                  </nav>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Trash Posts -->
                  <div class="tab-pane fade" id="trash-posts">
                      <!-- Trash posts content here -->
                  </div>
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

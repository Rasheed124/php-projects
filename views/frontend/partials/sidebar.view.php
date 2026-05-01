  <div class="sidebar">
              <div class="row">

           

                <!-- SEARCH AREA -->
                <div class="col-lg-12">
                    <div class="sidebar-item search">
                        <form id="search_form" name="gs" method="GET" action="<?php echo url('search'); ?>" style="position: relative;">
                        <?php $adminSupport->csrfField(); ?>

                        <input type="text" name="q" id="search_input" class="searchText" placeholder="type to search..." value="<?php echo $_GET['q'] ?? ''; ?>" autocomplete="off">

                        <!-- The dynamic button -->
                        <button type="submit" id="search_button" style="display: block; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fa fa-search"></i>
                        </button>
                        </form>
                    </div>
                </div>

                <!-- RECENT POSTS -->

                <div class="col-lg-12">
                    <div class="sidebar-item recent-posts">
                      <div class="sidebar-heading">
                          <h2>Recent Posts</h2>
                      </div>
                      <div class="content">
                          <ul>
                              <?php foreach ($recentPosts as $rp): ?>
                              <li>
                                  <a href="<?php echo url('post/' . $rp['slug']); ?>">
                                      <h5><?php echo e($rp['title']); ?></h5>
                                      <span><?php echo date('M d, Y', strtotime($rp['created_at'])); ?></span>
                                  </a>
                              </li>
                              <?php endforeach; ?>
                          </ul>
                      </div>
                  </div>
              </div>

                <!-- CATEGORIES  -->
                <div class="col-lg-12">
                  <div class="sidebar-item categories">
                      <div class="sidebar-heading">
                          <h2>Categories</h2>
                      </div>
                      <div class="content">
                          <ul>
                              <?php foreach ($categories as $cat): ?>
                              <li>
                                  <a href="<?php echo url('category/' . $cat['slug']); ?>">
                                      - <?php echo e($cat['name']); ?>
                                      (<?php echo $cat['post_count']; ?>)
                                  </a>
                              </li>
                              <?php endforeach; ?>
                          </ul>
                      </div>
                  </div>
                </div>

                <!-- TAGS  -->
                <div class="col-lg-12">
                    <div class="sidebar-item tags">
                      <div class="sidebar-heading">
                          <h2>Tags</h2>
                      </div>
                      <div class="content">
                          <ul>
                              <?php foreach ($tags as $tag): ?>
                              <li>
                                  <a href="<?php echo url('tag/' . urlencode($tag['name'])); ?>">
                                      <?php echo e($tag['name']); ?>
                                  </a>
                              </li>
                              <?php endforeach; ?>
                          </ul>
                      </div>
                  </div>
                </div>
              </div>
            </div>
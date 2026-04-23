    <!-- Banner Starts Here -->
    <div class="main-banner header-text">
      <div class="container-fluid">
        <div class="owl-banner owl-carousel">

        <!-- HEADER CAROUSEL BANNER -->


                  <?php foreach ($featuredPosts as $post): ?>
            <div class="item">
                <img class="" style="width: auto;  height: 310px;" src="<?php echo url($post['thumbnail'] ?? 'assets/frontend/images/default.jpg'); ?>" alt="">
                <div class="item-content">
                    <div class="main-content">
                        <div class="meta-category">
                            <span><?php echo e($post['category_name'] ?? 'General'); ?></span>
                        </div>
                        <a href="<?php echo url('post/' . $post['slug']); ?>"><h4><?php echo e($post['title']); ?></h4></a>
                        <ul class="post-info">
                            <li><a href="#"><?php echo e($post['author_name'] ?? 'Admin'); ?></a></li>
                            <li><a href="#"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
          <?php endforeach; ?>



        </div>
      </div>
    </div>



    <!-- Blog Posts Start Here -->
    <section class="blog-posts">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <!-- <div class="all-blog-posts">
              <div class="row">
                <div class="col-lg-12">
                  <div class="blog-post">
                    <div class="blog-thumb">
                      <img src="assets/frontend/images/blog-post-01.jpg" alt="">
                    </div>
                    <div class="down-content">
                      <span>Lifestyle</span>
                      <a href="post-details.html"><h4>Best Template Website for HTML CSS</h4></a>
                      <ul class="post-info">
                        <li><a href="#">Admin</a></li>
                        <li><a href="#">May 31, 2020</a></li>
                        <li><a href="#">12 Comments</a></li>
                      </ul>
                      <p>Stand Blog is a free HTML CSS template for your CMS theme. You can easily adapt or customize it for any kind of CMS or website builder. You are allowed to use it for your business. You are NOT allowed to re-distribute the template ZIP file on any template collection site for the download purpose. <a rel="nofollow" href="https://templatemo.com/contact" target="_parent">Contact TemplateMo</a> for more info. Thank you.</p>
                      <div class="post-options">
                        <div class="row">
                          <div class="col-6">
                            <ul class="post-tags">
                              <li><i class="fa fa-tags"></i></li>
                              <li><a href="#">Beauty</a>,</li>
                              <li><a href="#">Nature</a></li>
                            </ul>
                          </div>
                          <div class="col-6">
                            <ul class="post-share">
                              <li><i class="fa fa-share-alt"></i></li>
                              <li><a href="#">Facebook</a>,</li>
                              <li><a href="#"> Twitter</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="main-button">
                    <a href="blog.html">View All Posts</a>
                  </div>
                </div>
              </div>
            </div> -->

            <div class="all-blog-posts">
    <div class="row">
        <?php foreach ($latestPosts as $post): ?>
        <div class="col-lg-12">
            <div class="blog-post">
                <div class="blog-thumb">
                    <img style="width: 100%;  height: auto;"  src="<?php echo url($post['thumbnail']); ?>" alt="">
                </div>
                <div class="down-content">
                    <span><?php echo e($post['category_name']); ?></span>
                    <a href="<?php echo url('post/' . $post['slug']); ?>"><h4><?php echo e($post['title']); ?></h4></a>
                    <ul class="post-info">
                        <li><a href="#"><?php echo e($post['author_name']); ?></a></li>
                        <li><a href="#"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></a></li>
                    </ul>
                    <p><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">

              <!-- SEARCH AREA -->
                <div class="col-lg-12">
                  <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="GET" action="#">
                      <input type="text" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
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
          </div>
        </div>
      </div>
    </section>
    <!-- Blog Posts Ends  Here -->

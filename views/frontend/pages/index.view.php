<div class="main-banner header-text">
    <div class="container-fluid">
        <div class="owl-banner owl-carousel">
            <?php if (!empty($featuredPosts)): ?>
                <?php foreach ($featuredPosts as $post): ?>
                    <?php 
                        $rawTitle = $post['title'] ?? 'Untitled Post';
                        $shortTitle = mb_strimwidth($rawTitle, 0, 30, "..."); 
                    ?>
                    <div class="item">
                        <img style="width: 100%; height: 310px; object-fit: cover;" 
                            src="<?php echo url($post['thumbnail'] ?: '/assets/frontend/images/default.jpg'); ?>" 
                            alt="<?php echo e($rawTitle); ?>">
                        <div class="item-content">
                            <div class="main-content">
                                <div class="meta-category">
                                    <span><?php echo e($post['category_name'] ?? 'General'); ?></span>
                                </div>
                                <a href="<?php echo url('post/' . ($post['slug'] ?? '#')); ?>">
                                    <!-- Display the truncated title -->
                                    <h4><?php echo e($shortTitle); ?></h4>
                                </a>
                                <ul class="post-info">
                                    <li><a href="#"><?php echo e($post['author_name'] ?? 'Admin'); ?></a></li>
                                    <li><a href="#"><?php echo isset($post['created_at']) ? date('M d, Y', strtotime($post['created_at'])) : 'Recent'; ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="item">
                    <img style="width: 100%; height: 310px; object-fit: cover;" src="<?php echo url('assets/frontend/images/default.jpg'); ?>" alt="No Content">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category"><span>News</span></div>
                            <h4>Welcome to Tech Design Space</h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="blog-posts">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="all-blog-posts">
                    <div class="row">
                        <?php if (!empty($latestPosts)): ?>
                            <?php foreach ($latestPosts as $post): ?>
                                <div class="col-lg-12">
                                    <div class="blog-post">
                                        <div class="blog-thumb">
                                            <img style="width: 100%; height: auto;" 
                                                 src="<?php echo url($post['thumbnail'] ?: 'assets/frontend/images/default.jpg'); ?>" 
                                                 alt="<?php echo e($post['title']); ?>">
                                        </div>
                                        <div class="down-content">
                                            <span><?php echo e($post['category_name'] ?? 'Uncategorized'); ?></span>
                                            <a href="<?php echo url('post/' . ($post['slug'] ?? '#')); ?>">
                                                <h4><?php echo e($post['title'] ?? 'Untitled Post'); ?></h4>
                                            </a>
                                            <ul class="post-info">
                                                <li><a href="#"><?php echo e($post['author_name'] ?? 'Admin'); ?></a></li>
                                                <li><a href="#"><?php echo isset($post['created_at']) ? date('M d, Y', strtotime($post['created_at'])) : 'Recent'; ?></a></li>
                                            </ul>
                                            <p>
                                                <?php 
                                                    $content = strip_tags($post['content'] ?? '');
                                                    echo (strlen($content) > 150) ? substr($content, 0, 150) . '...' : $content; 
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-lg-12">
                                <div class="alert alert-info">
                                    No blog posts have been published yet. Please check back later!
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php require FRONTEND_VIEWS_PATH . '/partials/sidebar.view.php'; ?>
            </div>
        </div>
    </div>
</section>
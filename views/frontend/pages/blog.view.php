<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Blog Entries</h4>
                        <h2><?php echo e($title); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="blog-posts grid-system">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="all-blog-posts">
                    <div class="row">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="col-lg-6">
                                    <div class="blog-post">
                                        <div class="blog-thumb">
                                            <img src="<?php echo url($post['thumbnail'] ?? 'assets/images/default.jpg'); ?>" alt="">
                                        </div>
                                        <div class="down-content">
                                            <span><?php echo e($post['category_name'] ?? 'Uncategorized'); ?></span>
                                            <a href="<?php echo url('post/' . $post['slug']); ?>">
                                                <h4><?php echo e($post['title']); ?></h4>
                                            </a>
                                            <p><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="col-lg-12">
                                <ul class="page-numbers">
                                    <?php if ($pagination['has_prev']): ?>
                                        <li><a href="?page=<?php echo $pagination['current'] - 1; ?>"><i class="fa fa-angle-double-left"></i></a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <li class="<?php echo ($i == $pagination['current']) ? 'active' : ''; ?>">
                                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['has_next']): ?>
                                        <li><a href="?page=<?php echo $pagination['current'] + 1; ?>"><i class="fa fa-angle-double-right"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                        <?php else: ?>
                            <div class="col-lg-12">
                                <p>No posts found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php  require FRONTEND_VIEWS_PATH . '/partials/sidebar.view.php'; ?>
            </div>
        </div>
    </div>
</section>
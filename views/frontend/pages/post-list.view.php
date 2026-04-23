<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Archive</h4>
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
                        <?php if (! empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="col-lg-6">
                                    <div class="blog-post">
                                        <div class="blog-thumb">
                                            <img src="<?php echo url($post['thumbnail']); ?>" alt="">
                                        </div>
                                        <div class="down-content">
                                            <span><?php echo e($post['category_name']); ?></span>
                                            <a href="<?php echo url('post/' . $post['slug']); ?>"><h4><?php echo e($post['title']); ?></h4></a>
                                            <p><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No posts found in this section.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php //require FRONTEND_VIEWS_PATH . '/partials/sidebar.view.php'; ?>
            </div>
        </div>
    </div>
</section>
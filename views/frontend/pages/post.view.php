<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Post Details</h4>
                        <h2><?php echo e($post['title']); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<section class="call-to-action">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-content">
                    <div class="row">
                        <div class="col-lg-8">
                            <span>Currently reading: <?php echo e($post['category_name']); ?></span>
                            <h4><?php echo e($post['title']); ?></h4>
                        </div>
                        <div class="col-lg-4">
                            <div class="main-button">
                                <a href="<?php echo url('/'); ?>">Back to Blog</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-posts grid-system">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="all-blog-posts">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="blog-post">
                                <div class="blog-thumb">
                                    <img src="<?php echo url($post['thumbnail'] ?: 'assets/frontend/images/default-post.jpg'); ?>" alt="<?php echo 'post-banner-image'; ?>">
                                </div>
                                <div class="down-content">
                                    <span><?php echo e($post['category_name']); ?></span>
                                    <a href="javascript:void(0)"><h4><?php echo e($post['title']); ?></h4></a>
                                    <ul class="post-info">
                                        <li><a href="javascript:void(0)"><?php echo e($post['author_name']); ?></a></li>
                                        <li><a href="javascript:void(0)"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></a></li>
                                        <li><a href="javascript:void(0)">0 Comments</a></li>
                                    </ul>

                                    <div class="post-description mt-4">
                                        <?php echo $post['content']; ?>
                                    </div>

                                    <div class="post-options">
                                        <div class="row">
                                            <div class="col-6">
                                                <ul class="post-tags">
                                                    <li><i class="fa fa-tags"></i></li>
                                                    <?php
                                                        // Assuming you pass a 'post_tags' array in your controller
                                                        if (! empty($post_tags)):
                                                        foreach ($post_tags as $ptag): ?>
                                                            <li><a href="<?php echo url('tag/' . urlencode($ptag['name'])); ?>"><?php echo e($ptag['name']); ?></a>,</li>
                                                        <?php endforeach;
                                                        endif; ?>
                                                </ul>
                                            </div>
                                            <div class="col-6">
                                                <ul class="post-share">
                                                    <li><i class="fa fa-share-alt"></i></li>
                                                    <li><a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode(url('post/' . $post['slug'])); ?>" target="_blank">Facebook</a>,</li>
                                                    <li><a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(url('post/' . $post['slug'])); ?>" target="_blank">Twitter</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- COMMENTS AREA -->
                       
                        <div class="col-lg-12">
                             <?php require FRONTEND_VIEWS_PATH . '/partials/comments.view.php'; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
               <!-- SIDEBAR -->
                <?php require FRONTEND_VIEWS_PATH . '/partials/sidebar.view.php'; ?>

            </div>





            </div>
    </div>
</section>




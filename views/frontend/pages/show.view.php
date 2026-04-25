<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4><?php echo e($page->title ?? 'Page'); ?></h4>
                        <h2>
                            <?php echo !empty($page->title) ? 'Learn more ' . e($page->title) . ' Us'  : 'Information'; ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="about-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
                    <?php if (!empty($page->content)): ?>
                        <?php echo $page->content; ?>
                    <?php else: ?>
                        <div class="empty-page-state text-center" style="padding: 40px 0;">
                            <i class="fa fa-file-text-o" style="font-size: 48px; color: #eee; margin-bottom: 20px;"></i>
                            <h3>Content coming soon</h3>
                            <p>We are currently updating this page. Please check back later.</p>
                            <div class="main-button">
                                <a href="<?php echo url('blog'); ?>">Visit our Blog</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
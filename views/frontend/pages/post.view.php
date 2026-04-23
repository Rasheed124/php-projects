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
                                    <img src="<?php echo url($post['thumbnail'] ?: 'assets/frontend/images/default-post.jpg'); ?>" alt="<?php echo e($post['title']); ?>">
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
                                                    if(!empty($post_tags)): 
                                                        foreach($post_tags as $ptag): ?>
                                                            <li><a href="<?php echo url('tag/'.urlencode($ptag['name'])); ?>"><?php echo e($ptag['name']); ?></a>,</li>
                                                        <?php endforeach; 
                                                    endif; ?>
                                                </ul>
                                            </div>
                                            <div class="col-6">
                                                <ul class="post-share">
                                                    <li><i class="fa fa-share-alt"></i></li>
                                                    <li><a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode(url('post/'.$post['slug'])); ?>" target="_blank">Facebook</a>,</li>
                                                    <li><a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(url('post/'.$post['slug'])); ?>" target="_blank">Twitter</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="sidebar-item comments">
                                <div class="sidebar-heading">
                                    <h2>0 comments</h2>
                                </div>
                                </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sidebar-item search">
                                <form id="search_form" name="gs" method="GET" action="<?php echo url('search'); ?>">
                                    <input type="text" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                                </form>
                            </div>
                        </div>

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
                                                - <?php echo e($cat['name']); ?> (<?php echo $cat['post_count']; ?>)
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="sidebar-item tags">
                                <div class="sidebar-heading">
                                    <h2>Tag Clouds</h2>
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
<?php
    $mainComments = array_filter($comments, fn($c) => is_null($c['parent_id']));
    $replies      = array_filter($comments, fn($c) => ! is_null($c['parent_id']));
?>

<style>
    .pending-notice {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #f48840;
        background: rgba(244, 136, 64, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
        margin-top: 5px;
    }
    .is-pending {
        opacity: 0.8;
    }
</style>

<div class="sidebar-item comments">
    <div class="sidebar-heading">
        <h2><?php echo count($comments); ?> comments</h2>
    </div>
    <div class="content">
        <ul id="comment-list">
            <?php foreach ($mainComments as $c): ?>
                <li class="<?php echo($c['status'] === 'pending') ? 'is-pending' : ''; ?>">

                    <div class="author-thumb">
                        <?php
                            $avatar = (! empty($c['profile_image']))
                                ? asset('uploads/users/' . $c['profile_image'])
                                : asset('frontend/images/default-non-auth-user.jpg');
                        ?>
                        <img src="<?php echo $avatar; ?>" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="right-content">
                        <h4><?php echo e($c['auth_name'] ?? $c['author_name']); ?><span><?php echo date('M d, Y', strtotime($c['created_at'])); ?></span></h4>

                        <?php if ($c['status'] === 'pending'): ?>
                            <span class="pending-notice">Awaiting Moderation</span>
                        <?php endif; ?>

                        <p><?php echo e($c['content']); ?></p>
                    </div>
                </li>

                <?php foreach ($replies as $r): if ($r['parent_id'] == $c['id']): ?>
                    <li class="replied <?php echo($r['status'] === 'pending') ? 'is-pending' : ''; ?>">
                       <div class="author-thumb">
                            <?php
                                    $replyAvatar = (!empty($r['profile_image']))
                                            ? '/app/blog/' . ltrim($r['profile_image'], '/') 
                                            :asset('frontend/images/default-non-auth-user.jpg');
                            ?>
                            <img src="<?php echo $replyAvatar; ?>" alt="" style="width: 80px; height: 80px; object-fit: cover; ">
                        </div>
                        <div class="right-content">
                            <h4><?php echo e($r['auth_name'] ?? $r['author_name']); ?><span><?php echo date('M d, Y', strtotime($r['created_at'])); ?></span></h4>

                            <?php if ($r['status'] === 'pending'): ?>
                                <span class="pending-notice">Awaiting Moderation</span>
                            <?php endif; ?>

                            <p><?php echo e($r['content']); ?></p>
                        </div>
                    </li>
                <?php endif;endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="sidebar-item submit-comment">
    <div class="sidebar-heading"><h2>Your comment</h2></div>
    <div class="content">
        <form id="ajax-comment-form">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <div class="row">
                <?php if (! $currentUserId): ?>
                    <div class="col-md-6 col-sm-12">
                        <fieldset><input name="name" type="text" placeholder="Your name" required></fieldset>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <fieldset><input name="email" type="email" placeholder="Your email" required></fieldset>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12">
                    <fieldset><textarea name="message" rows="6" id="message" placeholder="Type your comment" required></textarea></fieldset>
                </div>
                <div class="col-lg-12">
                    <fieldset><button type="submit" id="form-submit" class="main-button">Submit</button></fieldset>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('ajax-comment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('form-submit');
    const formData = new FormData(this);

    btn.innerText = "Sending...";

    fetch("<?php echo url('comment/store'); ?>", { method: "POST", body: formData })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            // Match the new HTML structure with the persistent PHP logic
            const statusBadge = res.data.status === 'pending' ? '<span class="pending-notice">Awaiting Moderation</span>' : '';
            const pendingClass = res.data.status === 'pending' ? 'is-pending' : '';

            const newHtml = `
                <li class="${pendingClass}">
                    <div class="author-thumb"><img src="<?php echo asset('frontend/images/default-non-auth-user.jpg'); ?>"></div>
                    <div class="right-content">
                        <h4>${res.data.name} <span>${res.data.date}</span></h4>
                        ${statusBadge}
                        <p>${res.data.content}</p>
                    </div>
                </li>`;

            document.getElementById('comment-list').insertAdjacentHTML('beforeend', newHtml);
            this.reset();
        } else {
            alert(res.error);
        }
    })
    .finally(() => { btn.innerText = "Submit"; });
});
</script>
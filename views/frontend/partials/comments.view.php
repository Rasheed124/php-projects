<?php
    $currentUserId = $this->sessionController->getUserID();
    $isPostAuthor  = ($post['user_id'] == $currentUserId && $currentUserId !== null);
    $isAdmin       = $this->sessionController->isAdmin();

    // Display total comments fetched (Repository now handles the "Approved OR Mine" logic)
    $displayCount = count($comments);
?>

<style>
    .pending-comment { opacity: 0.7; background: #fff9e6; border-left: 4px solid #ffc107 !important; transition: 0.3s; }
    .replied { margin-left: 60px !important; margin-top: 20px; border-left: 2px solid #eee; padding-left: 20px; }
    .submitting { opacity: 0.5; pointer-events: none; }
    .badge-moderation { font-size: 10px; background: #ffc107; color: #000; padding: 2px 5px; border-radius: 3px; display: inline-block; margin-bottom: 5px; }
</style>

<div class="sidebar-item comments" id="comments-section">
    <div class="sidebar-heading">
        <h2><?php echo $displayCount; ?> Comments</h2>
    </div>
    <div class="content">
        <ul id="comment-list">
            <?php 
            $mainComments = array_filter($comments, fn($c) => is_null($c['parent_id']));
            $replies = array_filter($comments, fn($c) => !is_null($c['parent_id']));

            foreach ($mainComments as $comment): 
                $isPending = ($comment['is_approved'] == 0);
            ?>
                <li class="<?php echo $isPending ? 'pending-comment' : ''; ?>">
                    <div class="author-thumb">
                        <img src="<?php echo asset('frontend/images/default-non-auth-user.jpg'); ?>" alt="">
                    </div>
                    <div class="right-content">
                        <h4>
                            <?php echo e($comment['auth_name'] ?? $comment['guest_name']); ?>
                            <span><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                        </h4>
                        
                        <?php if($isPending): ?>
                            <span class="badge-moderation">Awaiting Moderation</span>
                        <?php endif; ?>

                        <p><?php echo e($comment['comment']); ?></p>
                    </div>
                </li>

                <?php foreach ($replies as $reply): 
                    if ($reply['parent_id'] == $comment['id']): 
                        $replyPending = ($reply['is_approved'] == 0);
                ?>
                    <li class="replied <?php echo $replyPending ? 'pending-comment' : ''; ?>">
                        <div class="author-thumb">
                            <img src="<?php echo asset('frontend/images/default-non-auth-user.jpg'); ?>" alt="">
                        </div>
                        <div class="right-content">
                            <h4>
                                <?php echo e($reply['auth_name'] ?? $reply['guest_name']); ?>
                                <span><?php echo date('M d, Y', strtotime($reply['created_at'])); ?></span>
                            </h4>
                            <?php if($replyPending): ?>
                                <span class="badge-moderation">Awaiting Moderation</span>
                            <?php endif; ?>
                            <p><?php echo e($reply['comment']); ?></p>
                        </div>
                    </li>
                <?php endif; endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="sidebar-item submit-comment" id="comment-form" style="margin-top: 50px;">
    <div class="sidebar-heading">
        <h2 id="respond-title">Leave a comment</h2>
    </div>
    <div class="content">
        <div id="comment-message" style="display:none;" class="alert"></div>

        <form id="ajax-comment-form">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="parent_id" value="">

            <div class="row">
                <?php if (!$currentUserId): ?>
                <div class="col-md-6 col-sm-12">
                    <fieldset>
                        <input name="name" type="text" placeholder="Your name" required>
                    </fieldset>
                </div>
                <div class="col-md-6 col-sm-12">
                    <fieldset>
                        <input name="email" type="email" placeholder="Your email" required>
                    </fieldset>
                </div>
                <?php endif; ?>

                <div class="col-lg-12">
                    <fieldset>
                        <textarea name="message" rows="6" id="message" placeholder="Type your comment" required></textarea>
                    </fieldset>
                </div>
                <div class="col-lg-12">
                    <fieldset>
                        <button type="submit" id="form-submit" class="main-button">Submit Comment</button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('ajax-comment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const btn = document.getElementById('form-submit');
    const msgDiv = document.getElementById('comment-message');
    const formData = new FormData(form);

    btn.innerText = "Processing...";
    form.classList.add('submitting');

    fetch("<?php echo url('comment/store'); ?>", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            msgDiv.className = "alert alert-success";
            msgDiv.innerHTML = data.message;
            msgDiv.style.display = "block";

            // Instant UI Update
            const name = formData.get('name') || "<?php echo $this->sessionController->getUserName(); ?>";
            const commentText = formData.get('message');
            const pendingBadge = !data.approved ? '<span class="badge-moderation">Awaiting Moderation</span>' : '';
            const pendingClass = !data.approved ? 'pending-comment' : '';

            const newHtml = `
                <li class="${pendingClass}">
                    <div class="author-thumb"><img src="<?php echo asset('frontend/images/default-non-auth-user.jpg'); ?>"></div>
                    <div class="right-content">
                        <h4>${name} <span>Just now</span></h4>
                        ${pendingBadge}
                        <p>${commentText}</p>
                    </div>
                </li>`;

            document.getElementById('comment-list').insertAdjacentHTML('beforeend', newHtml);
            form.reset();
        } else {
            msgDiv.className = "alert alert-danger";
            msgDiv.innerHTML = data.error;
            msgDiv.style.display = "block";
        }
    })
    .catch(err => {
        msgDiv.className = "alert alert-danger";
        msgDiv.innerHTML = "An error occurred. Please try again.";
        msgDiv.style.display = "block";
    })
    .finally(() => {
        btn.innerText = "Submit Comment";
        form.classList.remove('submitting');
    });
});
</script>
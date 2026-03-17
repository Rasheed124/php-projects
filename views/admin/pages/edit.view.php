<h1>Edit <?php echo $editpage->title ?> Page</h1>



<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach($errors AS $error): ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="index.php?<?php echo http_build_query(['route' => 'admin/pages/edit', 'id' => $editpage->id]); ?>">
     <input type="hidden" name="_csrf" value="<?php echo e(csrf_token()); ?>" />
    <label for="title">Title:</label>
 <input type="text" 
        name="title" 
        value="<?php if (isset($_POST['title'])) echo e($_POST['title']); else echo e($editpage->title);?>" 
        id="title" />


    <label for="content">Content:</lable>
    <textarea name="content" id="content"><?php if (isset($_POST['content'])) echo e($_POST['content']); else echo e($editpage->content);?></textarea>

    <input type="submit" value="Submit!" />
</form>
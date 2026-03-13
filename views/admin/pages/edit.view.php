<h1>Edit <?php echo $editpage->title ?> Page</h1>



<!-- Errroros -->

<form method="POST" action="index.php?route=admin/pages/create">
    <label for="title">Title:</label>
    <input type="text" 
        name="title" 
        value="<?php if (!empty($editpage->title)) echo e($editpage->title); ?>" 
        id="title" />

    <label for="slug">Slug:</label>
    <input type="text" 
        name="slug" 
        value="<?php if (!empty($editpage->slug)) echo e($editpage->slug); ?>" 
        id="slug" />

    <label for="content">Content:</lable>
    <textarea name="content" id="content"><?php if (!empty($editpage->content)) echo e($editpage->content); ?></textarea>

    <input type="submit" value="Submit!" />
</form>
<?php
    include './inc/functions.inc.php';
    include './inc/images.inc.php';

?>
<?php include './views/header.php'; ?>



<div class="gallery-container">

    <?php if (! empty($_GET['image']) && ! empty($imageTitles[$_GET['image']])): ?>

        <?php $currentImage = $_GET['image']; ?>


              <h3> <?php echo e($imageTitles[$currentImage]) ?></h3>

                <img src="./images/<?php echo rawurldecode($currentImage) ?>" alt="<?php echo $image_title ?>">


                <p> <?php echo str_replace("\n", "<br>", e($imageDescriptions[$currentImage])) ?> </p>

    <?php else: ?>

        <div class="notice">
     <p>Image Not Found</p>

        </div>
    <?php endif?>

    <a href="./index.php">Back to Gallery</a>

    <br> <br> <br> <br>



</div>




<?php include './views/footer.php'; ?>

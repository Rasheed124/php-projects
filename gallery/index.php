<?php
    include './inc/functions.inc.php';
    include './inc/images.inc.php';

?>
<?php include './views/header.php'; ?>




<div class="gallery-container">

    <?php if (! empty($imageTitles)): ?>

        <?php foreach ($imageTitles as $image_src => $image_title): ?>


             <a href="image.php?<?php echo http_build_query(['image' => $image_src])  ?>" class="gallery-item">
      
                <h3> <?php echo $image_title ?> </h3>

        

                <img src="./images/<?php echo rawurldecode($image_src) ?>" alt="<?php echo $image_title ?>">

             </a>


    <?php endforeach?>
    <?php endif?>

</div>







<?php include './views/footer.php'; ?>

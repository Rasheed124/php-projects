
<?php
    require __DIR__ . '/inc/functions.inc.php';

?>

<?php
    // $data = json_decode(file_get_contents('compress.bzip2://' . __DIR__ . '/./data/singapore.json.bz2'), true);
    $cities = json_decode(file_get_contents(__DIR__ . '/./data/index.json'), true);
?>

<?php require __DIR__ . '/views/header.inc.php'; ?>

<!-- <pre> -->
<?php if (! empty($cities)): ?>

    <ul>
        <?php foreach ($cities as $city): ?>
                <li>
                    <a href="city.php?<?php echo http_build_query(['city' => $city['city']]) ?>">

                        <?php echo $city['city'] . ''. e($city['flag']) ?>
                     </a>
                </li>

        <?php endforeach?>
    </ul>
<?php else: ?>
    <h2>No city available yet</h2>
<?php endif?>

<!-- </pre> -->


<?php require __DIR__ . '/views/footer.inc.php'; ?>
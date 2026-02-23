

<?php

    require __DIR__ . '/inc/functions.php';
    require __DIR__ . '/inc/db-connect-inc.php';

?>


    <!-- Fetch Diary  -->
  <!-- <pre> -->

    <?php
        // db, prepare execute
        $perpage = 2;
        $page    = (int) ($_GET['page'] ?? 1);

        if ($page < 1) {
            $page = 1;
        }

        // $page  = 1 , offeset ->  0
        // $page = 2,  offset ->  $perpage
        // $page = 3, offset ->  $perpage * 2
        $offset = ($page - 1) * $perpage;

        $stmt_page_count = $db->prepare("SELECT COUNT(*) AS `count` FROM `entries`;");
        $stmt_page_count->execute();
        $page_count = $stmt_page_count->fetch(PDO::FETCH_ASSOC)['count'];

        $num_pages = ceil($page_count / $perpage);

        // var_dump($num_pages);

        $stmt = $db->prepare("SELECT * FROM `entries` ORDER BY `date` DESC, `id` DESC LIMIT :perpage OFFSET :offset");
        $stmt->bindValue('perpage', (int) $perpage, PDO::PARAM_INT);
        $stmt->bindValue('offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

  <!-- </pre> -->


<?php require __DIR__ . '/views/header.view.php'?>

        <div class="container">
            <h1 class="main-heading">Entries</h1>
            <?php foreach ($entries as $entry): ?>

                <?php
                    $date           = $entry["date"];
                    $explodedformat = explode('-', $date);
                    $timeStamp      = mktime(12, 0, 0, $explodedformat[1], $explodedformat[2], $explodedformat[0]);
                    $formattedDate  = date('y/m/d', $timeStamp);
                ?>

                <div class="card">
                    <?php if (! empty($entry['image'])): ?>
                        <div class="card__image-container">

                            <img class="card__image" src="uploads/<?php echo e($entry['image']); ?>" alt="" />
                        </div>
                    <?php endif; ?>
                    <div class="card__desc-container">
                        <div class="card__desc-time"><?php echo e($formattedDate) ?></div>
                        <h2 class="card__heading"><?php echo e($entry["title"]) ?></h2>
                        <p class="card__paragraph">
                            <?php echo nl2br(e($entry["message"])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>



            <?php if ($num_pages > 1): ?>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="pagination__li">
                        <a class="pagination__link" href="index.php?<?php echo http_build_query(["page" => $page - 1]) ?>">⏴</a>
                    </li>
                <?php endif?>

                <?php for ($x = 1; $x <= $num_pages; $x++): ?>
                        <li class="pagination__li ">
                            <a class="pagination__link <?php if ($page === $x): ?>pagination__link--active<?php endif; ?>" href="index.php?<?php echo http_build_query(["page" => $x]) ?>"> <?php echo e($x) ?> </a>
                       </li>
                <?php endfor; ?>



                <?php if ($page < $num_pages): ?>
                    <li class="pagination__li">
                        <a class="pagination__link" href="index.php?<?php echo http_build_query(["page" => $page + 1]) ?>">⏵</a>
                    </li>
                <?php endif?>
            </ul>

            <?php endif?>
        </div>

<?php require __DIR__ . '/views/footer.view.php'?>

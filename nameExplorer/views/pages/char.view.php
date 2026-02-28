


    <ul>
        <?php foreach ($names as $name): ?>
            <li>
               <a href="name.php?<?php echo http_build_query(['name' => $name]) ?>">
                   <?php echo e($name) ?>
               </a>
            </li>
        <?php endforeach; ?>
    </ul>

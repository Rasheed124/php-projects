

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website <?php ?></title>
</head>
<body>


    <?php







<div class="gallery-container">

    <?php if (! empty($imageTitles)): ?>

        <?php foreach ($imageTitles as $image_key => $image_name): ?>


             <div class="gallery-item">
            <a href="<?php ?>">
                <h3> <?php echo $image_name ?> </h3>

            </a>

                <img src="./images/<?php echo $image_key ?>" alt="">

             </div>


    <?php endforeach?>
    <?php endif?>

</div>



<div class="gallery-container">

    <?php if (! empty($imageDescriptions)): ?>

        <?php foreach ($imageDescriptions as $image_key => $image_desc): ?>


             <div class="gallery-item">
                <img src="./images/<?php echo rawurlencode($image_key) ?>" alt="">

                <p> <?php echo $image_desc ?> </p>



             </div>


    <?php endforeach?>
    <?php endif?>

</div>














        $fruits      = ['apple', "pawpaw", "carrot"];
        $fruitsTwo   = ['apple', "pawpaw", "carrot"];
        $fruitsThree = ["orange"];

        // $all_fruits = array_merge($fruits, $fruitsTwo, $fruitsThree );
        $all_fruits = [ ...$fruits, ...$fruitsTwo, $fruitsThree];

        var_dump($all_fruits);

        // var_dump(array_search('carrot', $fruits)) . "\n" . "\n";

        // var_dump(array_slice($fruits, 1, 3));

        $fruits[2] = 'corn';

        $fruits[] = "orange";

        $choice = rand(0, count($fruits) - 1);

        // var_dump($fruits[$choice]);

        $new_fruits = array_unique($fruits);

        // var_dump($new_fruits);

        foreach ($new_fruits as $fruit) {

            if ($fruit === 'corn') {
                continue;
            }

            if ($fruit === 'watermelon') {
                continue;
            }

            // var_dump($fruit);

        }

        $nums = [10.345, 3];

        // echo round(...$nums) . "<br>";
        // echo round(10.345, 2);

    ?>
    <ul>

    <?php foreach ($new_fruits as $fruit): ?>
          <?php
              if ($fruit === 'corn') {
                  continue;
              }

              if ($fruit === 'watermelon') {
                  break;
              }

          ?>
                  <li> <?php echo $fruit ?></li>
            <?php endforeach?>



    </ul>




    <pre>

 <?php var_dump($_GET)?>

</pre>

<?php if (! empty($_GET['book'])): ?>

    <h1><?php echo $_GET['book'] ?></h1>

    <?php endif?>



    <a href="form.php?<?php echo http_build_query(['book' => 'Harry Potter']) ?>">Harry Potter</a> <br>
    <a href="form.php?<?php echo http_build_query(['book' => 'Beauty & the Beast', 'fruits' => 'mango']) ?>">Beauty & the Beast</a>






</body>
</html>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website <?php ?></title>

    <link rel="stylesheet" href="./css/simple.css">
</head>
<body>

<style>
    pre{
        background-color: rgba(0, 0, 0, 0.8);
        padding: 40px;
        width: 600px;
        margin:  0 auto;
        font-size: 15px;
        border: 1px solid black;
        margin-bottom: 20px;
}
</style>

<pre>

<?php

    if (! empty($_GET['page'])) {

    var_dump($_GET);
    }
?>
</pre>








    <?php
        function e($value)
        {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        $pages = [

            'citrus_salmon'       => 'Citrus Symphony Salmon',
            'mediterranian_pasta' => 'Mediterranean Marvel Pasta',
            'sunset_risotto'      => 'Sunset Risotto',
            'tropical_tacos'      => 'Tropical Tango Tacos',

        ];

    ?>



<form method="GET" action="form.php">
    <select name="page">
        <option value="">Please select a recipe</option>

 <?php foreach ($pages as $key => $value): ?>
                      <option value="<?php echo $key ?>" <?php if (! empty($_GET['page']) && $_GET['page'] === $key) {
          echo 'selected';
  }
  ?> > <?php echo $value ?> </option>
        <?php endforeach; ?>

    </select>
    <input type="submit" value="Submit!" />
</form>

<?php

    if (! empty($_GET['page'])) {

    $page = $_GET['page'];

    if ($pages[$page]) {

        echo file_get_contents("pages/{$page}.html");

    }
    }

?>





	</body>
	</html>

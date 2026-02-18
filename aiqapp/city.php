
<?php
    require __DIR__ . '/inc/functions.inc.php';

?>

<?php
    // $data = json_decode(file_get_contents('compress.bzip2://' . __DIR__ . '/./data/singapore.json.bz2'), true);
    $cities = json_decode(file_get_contents(__DIR__ . '/./data/index.json'), true);
?>

<?php
    $city = null;

    if (! empty($_GET['city'])) {
    $city = $_GET['city'];
    }

?>
<pre style="height: 600px;">

<?php
   


    $file_name = null;

    if (! empty($city)) {
    $cities = json_decode(file_get_contents(__DIR__ . '/./data/index.json'), true);

    foreach ($cities as $currentCity) {
        if ($currentCity['city'] === $city) {
            $file_name = $currentCity['filename'];
            $cityInformation =  $currentCity;
            break;
        }
    }
    }

    if (! empty($file_name)) {
    $results = json_decode(file_get_contents('compress.bzip2://' . __DIR__ . '/./data/singapore.json.bz2'), true)["results"];




    $units = [
        'pm10' => null,
        'pm25' => null,
    ];

    foreach ($results as $result) {

        if (! empty($units['pm10']) && ! empty($units['pm25'])) {
            break;
        }

        if ($result['parameter'] === 'pm10') {
            $units['pm10'] = $result['unit'];
        }
        if ($result['parameter'] === 'pm25') {
            $units['pm25'] = $result['unit'];
        }

    }

    $stats = [];
    foreach ($results as $result) {
        if ($result['parameter'] !== "pm25" && $result['parameter'] !== "pm10") {
            continue;
        }

        if ($result['value'] < 0) {
            continue;
        }

        $month = substr($result['date']['local'], 0, 7);
        if (! isset($stats[$month])) {
            $stats[$month] = [
                'pm25' => [],
                'pm10' => [],
            ];
        }
        $stats[$month][$result['parameter']][] = $result['value'];

    }

    // var_dump($stats);

    }

?>

</pre>

<?php require __DIR__ . '/views/header.inc.php'; ?>

<!-- <pre> -->
<?php if (empty($city)): ?>
    <h2>No city available yet</h2>

<?php else: ?>

    <h1><?php echo $cityInformation['city'] ?></h1>

     <?php if (! empty($stats)): ?>
             <table>

             <thead>
                <tr>
                    <th>Month</th>
                    <th>PM10 Meaurement</th>
                    <th>PM25 Measurement</th>
                </tr>
             </thead>

        <?php foreach ($stats as $month => $measure): ?>
                 <tr>
                      <th><?php echo e($month) ?></th>
                      <td><?php echo e(round(array_sum($measure['pm10']) / count($measure['pm10']), 2)) ?> <?php echo $units['pm10'] ?></td>
                      <td><?php echo e(round(array_sum($measure['pm25']) / count($measure['pm25']), 2)) ?> <?php echo $units['pm25'] ?></td>
                 </tr>
         <?php endforeach?>

             </table>

     <?php endif?>
<?php endif?>

<!-- </pre> -->


<?php require __DIR__ . '/views/footer.inc.php'; ?>
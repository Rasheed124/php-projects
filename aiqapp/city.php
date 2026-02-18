
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

<?php

    $file_name = null;

    if (! empty($city)) {
    $cities = json_decode(file_get_contents(__DIR__ . '/./data/index.json'), true);

    foreach ($cities as $currentCity) {
        if ($currentCity['city'] === $city) {
            $file_name       = $currentCity['filename'];
            $cityInformation = $currentCity;
            break;
        }
    }
    }

    // echo$file_name;

    if (! empty($file_name)) {
    $results = json_decode(file_get_contents('compress.bzip2://' . __DIR__ . "/./data/{$file_name}"), true)["results"];

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

    }

?>


<?php require __DIR__ . '/views/header.inc.php'; ?>

<?php if (empty($city)): ?>
    <h2>No city available yet</h2>

<?php else: ?>

    <h1><?php echo $cityInformation['city'] ?></h1>

    <canvas id="aqi-chart" style="width: 300px; height: 200px;"></canvas>
        <?php if (! empty($stats)): ?>

            <?php $labels = array_keys($stats);
                sort($labels);

                $pm25 = [];
                $pm10 = [];
                foreach ($labels as $label) {
                    $measurement = $stats[$label];

                    if (count($measurement['pm25']) !== 0) {
                        $pm25[] = array_sum($measurement['pm25']) / count($measurement['pm25']);

                    } else {
                        $pm25[] = 0;
                    }

                    if (count($measurement['pm10']) !== 0) {
                        $pm10[] = array_sum($measurement['pm10']) / count($measurement['pm10']);

                    } else {
                        $pm10[] = 0;
                    }

                }

                $datatsets = [];

                if (array_sum($pm25) > 0) {
                    $datatsets[] = [
                        'label' => "AQI, PM2.5 in {$units['pm25']}",
                        'data'        => $pm25,
                        'fill'        => false,
                        'borderColor' => 'rgb(192, 192, 192)',
                        'tension'     => 0.1,
                    ];
                }
                if (array_sum($pm10) > 0) {
                    $datatsets[] = [
                        'label' => "AQI, PM1.5 in {$units['pm10']}",
                        'data'        => $pm10,
                        'fill'        => false,
                        'borderColor' => 'rgb(164, 185, 29)',
                        'tension'     => 0.1,
                    ];
                }

            ?>


            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

            <script>

                document.addEventListener("DOMContentLoaded", function() {

                    const ctx = document.getElementById("aqi-chart");

                    const myChart = new Chart(ctx, {
                    type: 'line',
                    data:  {
                    labels: <?php echo json_encode($labels) ?>,
                    datasets: <?php echo json_encode($datatsets)  ?>
                    }

                  });
                })

            </script>

           <?php endif?>
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
                      <td>
                        <?php if (count($measurement['pm10']) !== 0): ?>
                          <?php echo e(round(array_sum($measure['pm10']) / count($measure['pm10']), 2)) ?> <?php echo $units['pm10'] ?>
                              <?php else: ?>
                            <i>No data available</i>
                        <?php endif; ?>
                     </td>

                    <td>
                        <?php if (count($measurement['pm25']) !== 0): ?>
                          <?php echo e(round(array_sum($measure['pm25']) / count($measure['pm25']), 2)) ?> <?php echo $units['pm25'] ?>
                              <?php else: ?>
                            <i>No data available</i>
                        <?php endif; ?>
                     </td>
                 </tr>
         <?php endforeach?>

             </table>

     <?php endif?>
<?php endif?>



<?php require __DIR__ . '/views/footer.inc.php'; ?>
<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/header.php';
?>
<div class="main-content">
    <section class="section">
        <!-- Alert Notifications -->
        <div class="row">
            <div class="col-12">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            <i class="fas fa-exclamation-triangle"></i> <?php echo e($error); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dynamic Statistics Cards -->
        <div class="row">
            <!-- Pages -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Pages</h5>
                                        <h2 class="mb-3 font-18"><?php echo number_format($stats['pages'] ?? 0); ?></h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="<?php echo asset('admin/img/banner/1.png'); ?>" alt="Pages">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Posts</h5>
                                        <h2 class="mb-3 font-18"><?php echo number_format($stats['posts'] ?? 0); ?></h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="<?php echo asset('admin/img/banner/2.png'); ?>" alt="Posts">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Comments</h5>
                                        <h2 class="mb-3 font-18"><?php echo number_format($stats['comments'] ?? 0); ?></h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="<?php echo asset('admin/img/banner/3.png'); ?>" alt="Comments">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Views -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Total Views</h5>
                                        <h2 class="mb-3 font-18"><?php echo number_format($stats['views'] ?? 0); ?></h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="<?php echo asset('admin/img/banner/4.png'); ?>" alt="Views">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Graph Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Activity Overview (<?php echo $isAdmin ? 'Global' : 'Personal'; ?>)</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="dashboardChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require __DIR__ . '/../../../views/admin/layouts/support-layouts/settingSiderbar.php'; ?>
</div>

<!-- Scripts for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pages', 'Posts', 'Comments'],
                datasets: [{
                    label: 'Content Count',
                    data: [
                        <?php echo $stats['pages'] ?? 0; ?>, 
                        <?php echo $stats['posts'] ?? 0; ?>, 
                        <?php echo $stats['comments'] ?? 0; ?>
                    ],
                    backgroundColor: [
                        'rgba(103, 119, 239, 0.6)', // Primary Blue
                        'rgba(251, 175, 190, 0.6)', // Soft Red
                        'rgba(71, 195, 99, 0.6)'    // Success Green
                    ],
                    borderColor: [
                        '#6777ef',
                        '#fbafbe',
                        '#47c363'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>

<?php
    require __DIR__ . '/../../../views/admin/layouts/support-layouts/footer.php';
?>
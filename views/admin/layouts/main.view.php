<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Otika - Admin Dashboard Template</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo asset('admin/css/app.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('admin/bundles/bootstrap-social/bootstrap-social.css'); ?>">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo asset('admin/css/style.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('admin/css/components.css'); ?>">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="<?php echo asset('admin/css/custom.css'); ?>">
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo asset('admin/img/favicon.ico'); ?>' />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />


</head>

<body>
  <div class="loader"></div>
  <div id="app">


      <!-- Main Content -->
      <?php echo $contents ?>


  </div>
  <!-- General JS Scripts -->

  <script src="<?php echo asset('admin/js/app.min.js'); ?>"></script>


  <!-- JS Libraies -->
  <!-- Template JS File -->
  <script src="<?php echo asset('admin/js/scripts.js'); ?>"></script>


<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


  <!-- Custom JS File -->
  <script src="<?php echo url('assets/admin/js/custom.js'); ?>"></script>
</body>


</html>

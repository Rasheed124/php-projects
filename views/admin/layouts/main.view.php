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


  <style>
    #app #post_tags_edit .choices__list--dropdown, .is_open .choices__list[aria-expanded]{
      position: absolute;
      width: 100%;
      z-index: 999;
}

#app #post_tags_edit .tox .tox-promotion{
    display:  none;
}


  </style>

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
<script src="https://cdn.tiny.cloud/1/<?php echo TINY_MCE_KEY; ?>/tinymce/8/tinymce.min.js"></script>

<script>
  tinymce.init({
selector: '#post_content_editor, #page_content_edit',
    height: 500,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
      'bold italic forecolor | alignleft aligncenter ' +
      'alignright alignjustify | bullist numlist outdent indent | ' +
      'removeformat | link image | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
     setup: function (editor) {
      editor.on('change', function () {
        editor.save();
      });
    },
    images_upload_url: '<?php echo url('/admin/posts/upload-post-image') ?>',

    // Better UX settings
    automatic_uploads: true,
    images_reuse_filename: false,
    file_picker_types: 'image',


    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,

    entity_encoding: "raw"
  });
</script>


  <!-- Custom JS File -->
  <script src="<?php echo url('assets/admin/js/custom.js'); ?>"></script>
</body>


</html>

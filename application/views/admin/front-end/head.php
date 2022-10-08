<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <script>
         base_url = "<?= base_url(); ?>";
    </script>
    <link rel="shortcut icon" href="<?= base_url('public/favicon.png') ?>" type="image/png">
    <!-- Bootstrap Css -->
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url('public/assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url('public/assets/css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--alert-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!--alert-->
</head>

<body>
    <?php
    if (!empty($this->session->flashdata('msg')) && !empty($this->session->flashdata('icon'))) :
    ?>
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: '<?= $this->session->flashdata('icon'); ?>',
                title: '<?= $this->session->flashdata('msg'); ?>'
            })
        </script>
    <?php endif; ?>
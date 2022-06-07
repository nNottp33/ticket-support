<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?= base_url() ?>/assets/images/logo.png">
    <title>Ticket support | <?php echo session()->get('class') == 'user' ? 'User' : 'Admin'; ?>
    </title>
    </title>
    <!-- Custom CSS -->
    <link href="<?= base_url() ?>/assets/extra-libs/c3/c3.min.css"
        rel="stylesheet">
    <link
        href="<?= base_url() ?>/assets/libs/chartist/dist/chartist.min.css"
        rel="stylesheet">
    <link
        href="<?= base_url() ?>/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css"
        rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="<?= base_url() ?>/dist/css/style.min.css"
        rel="stylesheet">

    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.jqueryui.min.css" type="text/css" />

    <!-- my style -->
    <link rel="stylesheet"
        href="<?= base_url() ?>/assets/css/index.css"
        type="text/css" />

    <!-- ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- select2 -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- sidebar -->
        <?= $this->include('/template/sidebar'); ?>
        <!-- end sidebar -->

        <!-- navbar -->
        <?= $this->include('/template/navbar'); ?>
        <!-- end navbar -->

        <?= $this->renderSection('content'); ?>
    </div>

    <!-- All Jquery -->

    <script src="<?= base_url() ?>/assets/libs/jquery/dist/jquery.min.js">
    </script>
    <script
        src="<?= base_url() ?>/assets/libs/popper.js/dist/umd/popper.min.js">
    </script>
    <script
        src="<?= base_url() ?>/assets/libs/bootstrap/dist/js/bootstrap.min.js">
    </script>
    <!-- apps -->
    <!-- apps -->
    <script src="<?= base_url() ?>/dist/js/app-style-switcher.js"></script>
    <script src="<?= base_url() ?>/dist/js/feather.min.js"></script>
    <script
        src="<?= base_url() ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js">
    </script>
    <script src="<?= base_url() ?>/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?= base_url() ?>/dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="<?= base_url() ?>/assets/extra-libs/c3/d3.min.js"></script>
    <script src="<?= base_url() ?>/assets/extra-libs/c3/c3.min.js"></script>
    <!-- <script src="<?= base_url() ?>/assets/libs/chartist/dist/chartist.min.js">
    </script>
    <script
        src="<?= base_url() ?>/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js">
    </script> -->
    <script
        src="<?= base_url() ?>/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js">
    </script>
    <script
        src="<?= base_url() ?>/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js">
    </script>
    <!-- <script src="<?= base_url() ?>/dist/js/pages/dashboards/dashboard1.min.js">
    </script> -->

    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.jqueryui.min.js"></script>

    <!-- sweet alert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- my script -->>
    <script src="<?= base_url() ?>/assets/js/app.js"></script>

    <!-- select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

</body>

</html>
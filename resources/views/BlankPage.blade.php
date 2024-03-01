<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Página sin titulo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Constructa ERP Gestion de recursos para constructoras." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Theme Config Js -->
        <script src="assets/js/config.js"></script>

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <!-- Begin page -->
        <div class="wrapper">

            

        <!-- Topbar -->
        @include('Templates.topbar')
        <!-- Topbar -->

        <!-- Sidebar -->
        @include('Templates.sidebar')
        <!-- Sidebar -->

            

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        
                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                @include('Templates.footer')
                <!-- End Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        

            

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>
        
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

    </body>
</html>

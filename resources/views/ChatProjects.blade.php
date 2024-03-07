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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">     
                                    <h4 class="page-title">Chat por proyecto</h4>              
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-8 col-lg-7">
                                <!-- project card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <select class="form-select form-select-sm">
                                                <option selected="">Proyecto ejemplo 1</option>
                                                <option value="1">Proyecto ejemplo 2</option>
                                                <option value="2">Proyecto ejemplo 3</option>
                                                <option value="3">Proyecto ejemplo 4</option>
                                            </select>
                                        </div>
                                        <!-- end dropdown-->

                                        <h4 class="mb-4 mt-0 fs-16">Proyecto ejemplo</h4>

                                        <div class="clerfix"></div>

                                        <div class="d-flex align-items-start">
                                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg" alt="Generic placeholder image" height="32" />
                                            <div class="w-100">
                                                <h5 class="mt-0">Nombre de usuario que dejó el mensaje<small class="text-muted float-end">Fecha timestamp</small></h5>
                                                Nice work, makes me think of The Money Pit.

                                                <br />

                                                
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start mt-3">
                                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32" />
                                            <div class="w-100">
                                                <h5 class="mt-0">Nombre de usuario que dejó el mensaje<small class="text-muted float-end">Fecha timestamp</small></h5>
                                                It would be very nice to have.

                                                <br />
                                            </div>
                                        </div>



                                        <div class="border rounded mt-4">
                                            <form action="#" class="comment-area-box">
                                                <textarea rows="3" class="form-control border-0 resize-none" placeholder="Agregar comentario"></textarea>
                                                <div class="p-2 bg-light d-flex justify-content-between align-items-center">

                                                    <button type="submit" class="btn btn-sm btn-success"><i class="ri-send-plane-2 me-1"></i>ENVIAR</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end .border-->
                                    </div>
                                    <!-- end card-body-->
                                </div>
                                <!-- end card-->

                                
                            </div>
                            <!-- end col -->

                            <div class="col-xl-4 col-lg-5">
                                <div class="card">
                                    <div class="card-body">
                                        

                                        <h5 class="card-title fs-16 mb-3">ADJUNTOS</h5>

                                        <form action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                            <div class="fallback">
                                                <input name="file" type="file" />
                                            </div>

                                            <div class="dz-message needsclick">
                                                <i class="fs-36 text-muted ri-upload-cloud-line"></i>
                                                <h4>Arrastra el archivo acá o presiona para subir</h4>
                                            </div>
                                        </form>

                                        <!-- Preview -->
                                        <div class="dropzone-previews mt-3" id="file-previews"></div>

                                        <!-- file preview template -->
                                        <div class="d-none" id="uploadPreviewTemplate">
                                            <div class="card mb-1 mb-0 shadow-none border">
                                                <div class="p-2">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="" />
                                                        </div>
                                                        <div class="col ps-0">
                                                            <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                                            <p class="mb-0" data-dz-size></p>
                                                        </div>
                                                        <div class="col-auto">
                                                            <!-- Button -->
                                                            <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                                                <i class="ri-close-line"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end file preview template -->

                                 

                                        <div class="card mb-1 shadow-none border">
                                            <div class="p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                     
                                                    </div>
                                                    <div class="col ps-0">
                                                        <a href="javascript:void(0);" class="text-muted fw-bold">Dashboard-design.jpg</a>
                                                        <p class="mb-0">3.25 MB</p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <!-- Button -->
                                                        <a href="javascript:void(0);" class="btn btn-link fs-16 text-muted">
                                                            <i class="ri-download-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-0 shadow-none border">
                                            <div class="p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
            
                                                    </div>
                                                    <div class="col ps-0">
                                                        <a href="javascript:void(0);" class="text-muted fw-bold">Admin-bug-report.mp4</a>
                                                        <p class="mb-0">7.05 MB</p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <!-- Button -->
                                                        <a href="javascript:void(0);" class="btn btn-link fs-16 text-muted">
                                                            <i class="ri-download-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                @include('Templates.footer')
                <!-- end Footer -->

            </div>
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

        <script src="assets/vendor/dropzone/min/dropzone.min.js"></script>
        <!-- init js -->
        <script src="assets/js/pages/component.fileupload.js"></script>

    </body>
</html>

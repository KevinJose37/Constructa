<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard Principal</title>
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
        <?php include_once(__DIR__ . '/templates/topbar.php'); ?>
        <!-- Topbar -->

        <!-- Sidebar -->
        <?php include_once(__DIR__ . '/templates/sidebar.php'); ?>
        <!-- Sidebar -->



        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                        <h4 class="page-title">Gestión de proyectos</h4>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                                        <h4 class="page-title">Tabla de proyectos</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#event-modal">Crear un nuevo proyecto</button>
                                            </li>

                                        </ol>
                                    </div>



                                    <div class="table-responsive-sm">
                                        <table class="table table-striped table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del proyecto</th>
                                                    <th>Responsable del proyecto</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Prueba</td>
                                                    <td>Prueba</td>
                                                    <td>Prueba</td>
                                                    <td style="display: flex; align-items: center;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-settings-3-line"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-animated">
                                                                <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#event-modal-editar" data-project-id="">Editar proyecto</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Gestionar materiales</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Gestionar finanzas</a>
                                                                <a href="" class="dropdown-item" onclick="return confirm('¿Estás seguro de querer eliminar este proyecto?');">Eliminar proyecto</a>
                                                            </div>
                                                        </div>

                                                        <a href="javascript:void(0);" class="text-reset fs-19 px-1" onclick="eliminarProyecto()"> <i class="ri-delete-bin-2-line"></i></a>
                                                    </td>



                                                </tr>

                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->


                    </div>


                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include_once(__DIR__ . '/templates/footer.php'); ?>
            <!-- End Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
    <!-- MODAL CREAR PROYECTO -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="needs-validation" name="event-form" id="form-event" action="../Controller/projectinsert.php" method="POST" novalidate>
                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title">Crear nuevo proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Nombre del proyecto</label>
                                    <input class="form-control" placeholder="Insertar nombre de proyecto" type="text" name="projectName" id="project-name" required />
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Encargado del proyecto</label>
                                    <input class="form-control" placeholder="Ingresar nombre del encargado" type="text" name="projectOwner" id="project-owner" required />
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Estado</label>
                                    <select class="form-select" name="projectStatus" id="project-status" required>
                                        <option value="En progreso" selected>En progreso</option>
                                        <option value="Pausado">Pausado</option>
                                        <option value="No iniciado">No iniciado</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Indefinido">Indefinido</option>
                                    </select>
                                    <div class="invalid-feedback">Selecciona una categoria válida</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <!-- <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button> -->
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" id="btn-save-event">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end modal-content-->
        </div> <!-- end modal dialog-->
    </div>
    <!-- end modal-->
    <script>
        $('#event-modal-editar').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var projectId = button.data('project-id'); // Extrae el ID del proyecto del atributo data-project-id

            // Establece el valor del ID del proyecto en el campo oculto del formulario
            var modal = $(this);
            modal.find('#id_proyecto').val(projectId);
        });
    </script>
    <!-- MODAL EDITAR PROYECTO -->
    <div class="modal fade" id="event-modal-editar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="needs-validation" name="event-form" id="form-event" action="../Controller/editproject.php" method="POST" novalidate>
                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title">Editar proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Nombre del proyecto</label>
                                    <input class="form-control" placeholder="Insertar nombre de proyecto" type="text" name="projectName" id="project-name" required />
                                    <input type="hidden" name="projectId" id="id_proyecto">
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Encargado del proyecto</label>
                                    <input class="form-control" placeholder="Ingresar nombre del encargado" type="text" name="projectOwner" id="project-owner" required />
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Estado</label>
                                    <select class="form-select" name="projectStatus" id="project-status" required>
                                        <option value="En progreso" selected>En progreso</option>
                                        <option value="Pausado">Pausado</option>
                                        <option value="No iniciado">No iniciado</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Indefinido">Indefinido</option>
                                    </select>
                                    <div class="invalid-feedback">Selecciona una categoria válida</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <!-- <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button> -->
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" id="btn-save-event">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end modal-content-->
        </div> <!-- end modal dialog-->
    </div>
    <!-- end modal-->






    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>
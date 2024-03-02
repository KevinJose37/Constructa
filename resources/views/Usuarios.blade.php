<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Usuarios</title>
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

<body class="show">
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

                    <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                        <h4 class="page-title">Gestión de usuarios</h4>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                                        <h4 class="page-title">Tabla de usuarios</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#event-modal">Crear un nuevo usuario</button>
                                            </li>

                                        </ol>
                                    </div>
                                    <div class="table-responsive-sm">
                                        <form class="needs-validation" name="event-form-editar" id="form-event-editar" action="" method="POST" novalidate>

                                            <div class="col-lg-3 col-md-6 ">
                                                <div class="input-group">
                                                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                                                    <input type="text" name="filter" id="" class="form-control" value="">
                                                    <button class="btn" id="clear-filter"><i class="ri-close-line"></i></button>
                                                </div>
                                            </div>
                                        </form>

                                        <table class="table table-striped table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nombre de usuario</th>
                                                    <th>Correo electrónico</th>
                                                    <th>Rol</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->rol->rol_name }}</td>
                                                    <td style="display: flex; align-items: center;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-settings-3-line"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-animated">
                                                                <a href="javascript:void(0);" class="dropdown-item edit-project-btn" data-bs-toggle="modal" data-bs-target="#event-modal-editar">Editar usuario</a>
                                                                <a href="javascript:void(0);" class="dropdown-item">Gestionar proyectos</a>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="text-reset fs-19 px-1 delete-project-btn"> <i class="ri-delete-bin-2-line"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div> <!-- end table-responsive-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->

                        <!-- MODAL CREAR USUARIO -->
                        <div class="modal fade" id="event-modal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                        <form class="needs-validation" name="event-form" id="form-event" action="{{ route('usuarios.store') }}" method="POST" novalidate>
                                        @csrf
                                        <div class="modal-header py-3 px-4 border-bottom-0">
                                            <h5 class="modal-title" id="modal-title">Crear nuevo usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 pb-4 pt-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Nombre de usuario</label>
                                                        <input class="form-control" placeholder="Insertar nombre de usuario" type="text" name="name" id="user-name" required />
                                                        <div class="invalid-feedback">Escriba un nombre de usuario valido</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Contraseña de usuario</label>
                                                        <input class="form-control" placeholder="Contraseña" type="password" name="user_password" id="user-password" minlength="8" required />
                                                        <div class="invalid-feedback">Escriba una contraseña válida de minimo 8 caracteres</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Email Usuario</label>
                                                        <input class="form-control" placeholder="Email de usuario" type="text" name="email" id="email-user" required />
                                                        <div class="invalid-feedback">Escriba una email válido</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Rol</label>
                                                        <select class="form-select" name="rol_id" id="rol-user" required>
                                                            <option value="1">Administracion</option>
                                                            <option value="2">Gerente de obra</option>
                                                            <option value="3">Empleado</option>
                                                        </select>
                                                        <div class="invalid-feedback">Selecciona una categoría válida</div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-6">
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
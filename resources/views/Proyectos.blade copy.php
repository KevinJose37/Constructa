<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Proyectos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Constructa ERP Gestion de recursos para constructoras." name="description">
    <meta content="Coderthemes" name="author">
    @livewireStyles
    <!-- App favicon -->


    <!-- Dynamic JavaScript resources -->
    @vite(['resources/js/Proyectos/projects.js'])
</head>
<body class="show">
    @livewireScripts
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

                <div class="container-fluid">

                    <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                        <h4 class="page-title">Gestión de proyectos</h4>

                    </div>
                    <livewire:show-projects />

                    {{-- <div class="row">
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
                                        <form class="needs-validation" name="event-form-editar" id="form-event-editar" action="{{ route('projects.index')}}" method="GET" novalidate>
                                            @csrf
                                            <div class="col-lg-3 col-md-6 ">
                                                <div class="input-group">
                                                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                                                    <input type="text" name="filter" id="" class="form-control" placeholder="Buscar por nombre de proyecto" value="{{ isset($filter) ? $filter : ''}}">
                                                    <button class="btn" id="clear-filter"><i class="ri-close-line"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <table class="table table-striped table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del proyecto</th>
                                                    <th>Descripción del proyecto</th>
                                                    <th>Estado</th>
                                                    <th>Fecha inicio</th>
                                                    <th>Fecha estimada fin</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($projects as $project)
                                                <tr>
                                                    <td>{{ $project->project_name }}</td>
                                                    <td>{{ $project->project_description }}</td>
                                                    <td>{{ $project->projectStatus->status_name }}</td>
                                                    <td>{{ $project->project_start_date }}</td>
                                                    <td>{{ $project->project_estimated_end }}</td>
                                                    <td style="display: flex; align-items: center;">
                                                        <div class="dropdown">
                                                            
                                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="ri-settings-3-line"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-animated">
                                                                <a href="javascript:void(0);" class="dropdown-item edit-project-btn" data-bs-toggle="modal" data-bs-target="#event-modal-editar" data-project-id="{{ $project->id }}">Editar proyecto</a>
                                                                <a href="javascript:void(0);" class="dropdown-item" data-project-id="{{ $project->id }}">Gestionar materiales</a>
                                                                <a href="javascript:void(0);" class="dropdown-item" data-project-id="{{ $project->id }}">Gestionar finanzas</a>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:void(0);" class="text-reset fs-19 px-1 view-users"  id="view-users" data-project-id="{{ $project->id }}"> <i class="ri-presentation-line"></i></a>
                                                        <a href="#" class="text-reset fs-19 px-1 delete-project-btn" onclick="confirmDelete('{{ $project->id }}')"> <i class="ri-delete-bin-2-line"></i></a>

                                                        <!-- Formulario oculto para enviar la solicitud DELETE -->
                                                        <form id="delete-project-form-{{ $project->id }}" action="{{ route('projects.destroy', ['id' => $project->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $projects->links('pagination::bootstrap-4') }}
                                    </div> <!-- end table-responsive-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->


                    </div> --}}


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
    <!-- MODAL CREAR PROYECTO -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="needs-validation" name="event-form" id="form-event" action="{{ route('projects.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title">Crear nuevo proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Nombre del proyecto</label>
                                    <input class="form-control" placeholder="Insertar nombre de proyecto" type="text" name="project_name" id="project-name" required />
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Descripción del proyecto</label>
                                    <input class="form-control" placeholder="Ingresar descripción del proyecto" type="text" name="project_description" id="project-description" required />
                                    <div class="invalid-feedback">Escriba una descripción válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Estado del proyecto</label>
                                    <select class="form-select" name="project_status_id" id="project-status" required>
                                        <option value="1" selected>En progreso</option>
                                        <option value="2">Finalizado</option>
                                        <option value="3">No iniciado</option>
                                        <option value="4">Cancelado</option>
                                        <option value="5">En pausa</option>
                                    </select>
                                    <div class="invalid-feedback">Selecciona una categoría válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" name="project_start_date" id="project-start-date" required>
                                    <div class="invalid-feedback">Ingrese una fecha de inicio válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha estimada de finalización</label>
                                    <input type="date" class="form-control" name="project_estimated_end" id="project-estimated-end" required>
                                    <div class="invalid-feedback">Ingrese una fecha estimada de finalización válida</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <!-- Puedes añadir botones adicionales si lo necesitas -->
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
{{-- 

    <!--EDITAR MODAL-->
    <div class="modal fade" id="event-modal-editar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <form class="needs-validation" name="event-form-editar" id="form-event-editar" action="{{ route('projects.update', ['id' => $project->id]) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title-editar">Editar proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Nombre del proyecto</label>
                                    <input class="form-control" placeholder="Insertar nombre de proyecto" type="text" name="project_name" id="project-name-editar" required />
                                    <input type="hidden" name="project_id" id="project-id-editar">
                                    <div class="invalid-feedback">Escriba un nombre válido</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Descripción del proyecto</label>
                                    <input class="form-control" placeholder="Ingresar descripción del proyecto" type="text" name="project_description" id="project-description-editar" required />
                                    <div class="invalid-feedback">Escriba una descripción válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Estado del proyecto</label>
                                    <select class="form-select" name="project_status_id" id="project-status-editar" required>
                                        <option value="1" selected>En progreso</option>
                                        <option value="2">Finalizado</option>
                                        <option value="3">No iniciado</option>
                                        <option value="4">Cancelado</option>
                                        <option value="5">En pausa</option>
                                    </select>
                                    <div class="invalid-feedback">Selecciona una categoría válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" name="project_start_date" id="project-start-date-editar" required>
                                    <div class="invalid-feedback">Ingrese una fecha de inicio válida</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha estimada de finalización</label>
                                    <input type="date" class="form-control" name="project_estimated_end" id="project-estimated-end-editar" required>
                                    <div class="invalid-feedback">Ingrese una fecha estimada de finalización válida</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" id="btn-save-event-editar">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end modal-content-->
        </div> <!-- end modal dialog-->
    </div>
    <!-- end modal-->

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

                        
                    

                       <!-- MODAL GESTIONAR PROYECTOS POR USUARIO-->
                    <div class="modal fade" id="event-modal-proyectosusuario" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header py-3 px-4 border-bottom-0">
                                            <h5 class="modal-title" id="modal-title">USUARIOS ASIGNADOS</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 pb-4 pt-0" id="modal-body-users">
                                            <div class="row">
                                                <div class="col-6">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer px-4 pb-4 pt-0" id="modal-body-users">
                                            <div class="col-6 text-end">
                                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                            </div> <!-- end modal-content-->
                        </div> <!-- end modal dialog-->
                    </div>





    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- Flatpickr Timepicker Plugin js -->
    <script src="assets/vendor/flatpickr/flatpickr.min.js"></script>
    <!-- Timepicker Demo js -->
    <script src="assets/js/pages/demo.flatpickr.js"></script>

    <script>
        
        flatpickr("#start-datepicker", {
            dateFormat: "Y-m-d",
        });

        flatpickr("#end-datepicker", {
            dateFormat: "Y-m-d",
        });
        flatpickr("#start-datepickerCreate", {
            dateFormat: "Y-m-d",
        });

        flatpickr("#end-datepickerCreate", {
            dateFormat: "Y-m-d",
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clear-filter').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('input[name="filter"]').value = ''; // Limpiar el campo de búsqueda
                window.location.href = "{{ route('projects.index') }}";
            });
        });

        $('.edit-project-btn').click(function() {
            var projectId = $(this).data('project-id');
            $('#project-id-editar').val(projectId);
        });

        function confirmDelete(projectId) {
            if (confirm('¿Estás seguro de querer eliminar el proyecto?')) {
                document.getElementById('delete-project-form-' + projectId).submit();
            } else {
                return false;
            }
        }
    </script>
 --}}

</body>

</html>
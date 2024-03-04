<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <h3>Detalles del Proyecto</h3>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p><strong>Nombre del Proyecto:</strong> {{ $project->project_name }}</p>
                        <p><strong>Descripción del Proyecto:</strong> {{ $project->project_description }}</p>
                    </div>
                    <div class="col">
                        <p><strong>Fecha de Inicio:</strong> {{ $project->project_start_date }}</p>
                        <p><strong>Fecha Estimada de Fin:</strong> {{ $project->project_estimated_end }}</p>
                    </div>
                </div>
                @if (!$userInfo->isEmpty())
                <div class="row">
                    <div class="col">
                        <button type="button" id="event-assign-project" class="btn btn-primary w-100">Asignar nueva persona</button>
                    </div>
                    <div class="row mt-2">
                            <div class="col">
                                <input type="hidden" name="idProject" id="idProject" value="{{$project->id}}">
                                <select name="idUser" id="all-users-assign"  class="form-select " style="display: none">
                                    <option value="" disabled selected>Seleccione una opción...</option>
                                    @foreach ($userInfo as $userId => $userName)
                                    <option value="{{$userId}}">{{$userName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col col-md-auto">
                                <button class="btn btn-success" id="accept-assign" style="display: none" ><i class="ri-send-plane-fill"></i></button>
                            </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <h3>Usuarios Asociados</h3>
            <table class="table table-striped table-centered mb-0">
                <thead>
                    <tr>
                        <th>Nombre del Usuario</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a class="text-reset fs-19 px-1 delete-user-project-btn" > <i class="ri-delete-bin-2-line"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tu script JavaScript aquí
    console.log('Este es mi script JavaScript');
</script>

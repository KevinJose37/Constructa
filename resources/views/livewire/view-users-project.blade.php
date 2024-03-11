<div>
    <a href="javascript:void(0);" class="text-reset fs-19 px-1 view-users" id="view-users"
        wire:click="$set('formUsers.open', true)"> <i class="ri-presentation-line"></i></a>

    <x-dialog-modal wire:model="formUsers.open" maxWidth="md" id="{{$project->id}}">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h3>Detalles del Proyecto #{{ $project->id }}</h3>
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <p><strong>Nombre del Proyecto:</strong> {{ $project->project_name }}</p>
                                    <p><strong>Descripción del Proyecto:</strong> {{ $project->project_description }}
                                    </p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col">
                                    <p><strong>Fecha de Inicio:</strong> {{ $project->project_start_date }}</p>
                                    <p><strong>Fecha Estimada de Fin:</strong> {{ $project->project_estimated_end }}</p>
                                </div>
                            </div>
                            @if (!$usersNotAssigned->isEmpty())
                                <div class="row">
                                    <div class="col">
                                        <button type="button" id="event-assign-project" wire:click="toggleSelect"
                                            class="btn btn-primary w-100">Asignar nueva persona</button>
                                    </div>
                                    @if($openSelect)
                                        <div class="row mt-2">
                                            <div class="col">
                                                <select name="idUser" id="all-users-assign" class="form-select" wire:model="formUsers.user_select">
                                                    <option value="-" disabled selected>Seleccione una opción...
                                                    </option>
                                                    @foreach ($usersNotAssigned as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col col-md-auto">
                                                <button class="btn btn-success" id="accept-assign" wire:click="store"><i
                                                        class="ri-send-plane-fill"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="idProject" id="idProject" value="{{ $project->id }}">
                        <h3>Usuarios Asociados</h3>
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre del Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->rol->name }}</td>
                                        <td><a class="text-reset fs-19 px-1 delete-user-project-btn"
                                                data-id-user="{{ $user->id }}"> <i
                                                    class="ri-delete-bin-2-line"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">
            <div class="row w-100">
                <div class="col-6 ">
                    <button type="button" class="btn btn-light me-1"
                        wire:click="$set('formUsers.open', false)">Cerrar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

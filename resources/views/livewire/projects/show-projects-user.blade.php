<div>

    <a href="javascript:void(0);" class="text-reset fs-19 px-1" wire:click="$set('open', true)"
        data-user-id="{{ $user->id }}"> <i class="ri-presentation-line"></i></a>

    <x-dialog-modal wire:model="open" maxWidth="md" id="{{ $user->id }}-view-user">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h3>Información del usuario {{ $user->name }}</h3>
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        @can('assign.user.project')
                                            <button type="button" id="event-assign-project" wire:click="toggleSelect"
                                                class="btn btn-primary w-100">Asignar nuevo proyecto</button>
                                        @endcan
                                        @if ($openSelect)
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <select name="idUser" id="all-users-assign" class="form-select"
                                                        wire:model="project_select">
                                                        <option value="-" disabled selected>Seleccione una
                                                            opción...
                                                        </option>
                                                        @foreach ($projectNotAssign as $proj)
                                                            <option value="{{ $proj->id }}">
                                                                {{ $proj->project_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col col-md-auto">
                                                    <button class="btn btn-success" id="accept-assign"
                                                        wire:click="store"><i class="ri-send-plane-fill"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($projectsUser->isEmpty())
                                            <div class="row mt-2">
                                                <p>El usuario no tiene proyectos asignados</p>
                                            </div>
                                        @else
                                            <table class="table table-striped table-centered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre del proyecto</th>
                                                        <th>Estatus del proyecto</th>
                                                        <th>Fecha de asignacion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @dd($projectsUser) --}}
                                                    @foreach ($projectsUser as $projectAssing)
                                                        <tr>
                                                            <td>{{ $projectAssing->project_name }}</td>
                                                            <td>{{ $projectAssing->projectStatus->status_name }}</td>
                                                            <td>{{ $projectAssing->pivot->created_at }}</td>
                                                            @can('unassign.user.project')
                                                                <td><a class="text-reset fs-19 px-1 delete-user-project-btn"
                                                                        wire:click="destroyAlertProject({{ $projectAssing->id }}, '{{ $projectAssing->project_name }}')">
                                                                        <i class="ri-delete-bin-2-line"></i></a></td>
                                                            @endcan

                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">
            <div class="row w-100">
                <div class="col-6 ">
                    <button type="button" class="btn btn-light me-1" wire:click="$set('open', false)">Cerrar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

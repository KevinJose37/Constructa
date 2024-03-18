<div>
    <x-table>
        @if ($projects->isEmpty() && auth()->user()->hasRole('Empleado'))
            <div class="w-100 h-100">
                <h2>¡No tienes ningún proyecto asignado!</h2>
            </div>
        @else
            <div class="row w-100">
                <div class="col-lg-6 w-25">
                    <!-- Div a la izquierda -->
                    <div class="input-group">
                        <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                        <input type="text" name="filter" class="form-control"
                            placeholder="Buscar por nombre de proyecto" wire:model.live="search">
                        <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i
                                class="ri-close-line"></i></button>
                    </div>
                </div>
                @can('store.project')
                    <livewire:create-project></livewire:create-project>
                @endcan
            </div>
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
                    @foreach ($projects as $project)
                        <tr id="projectRow_{{ $project->id }}">
                            <td>{{ $project->project_name }}</td>
                            <td>{{ $project->project_description }}</td>
                            <td>{{ $project->projectStatus->status_name }}</td>
                            <td>{{ $project->project_start_date }}</td>
                            <td>{{ $project->project_estimated_end }}</td>
                            <td style="display: flex; align-items: center;">
                                <div class="dropdown">

                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-settings-3-line"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-animated">
                                        <a href="javascript:void(0);" class="dropdown-item">Gestionar materiales</a>
                                        <a href="javascript:void(0);" class="dropdown-item">Gestionar finanzas</a>
                                    </div>
                                </div>
                                <livewire:view-users-project :$project :wire:key="'view-' . $project->id"></livewire:view-users-project>
                                @can('delete.project')
                                    <a href="#" class="text-reset fs-19 px-1 delete-project-btn"
                                        wire:click.prevent="destroyAlert({{ $project->id }}, '{{ $project->project_name }}')">
                                        <i class="ri-delete-bin-2-line"></i></a>
                                @endcan
                                @can('update.project')
                                    <livewire:update-project :$project :wire:key="'update-' . $project->id"></livewire:update-project>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $projects->links(data: ['scrollTo' => false]) }}
        @endif

    </x-table>
</div>
@push('js')
    <script></script>
@endpush

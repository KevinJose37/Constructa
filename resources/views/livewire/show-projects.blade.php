<div>
    <x-page-title title="Tabla de proyectos"></x-page-title>
    <x-table>
        @if ($projects->isEmpty() && auth()->user()->hasRole('Residente'))
            <div class="w-100 h-100">
                <h2>¡No tienes ningún proyecto asignado!</h2>
            </div>
        @else
            <div class="row w-100 mb-3">
                <div class="col-lg-6">
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
            <div class="table-container">
                <table class="table table-striped table-centered mb-0">
                    <thead>
                        <tr>
                            <th>Nombre del proyecto</th>
                            <th>Número de contrato</th>
                            <th>Objeto Contractual</th>
                            <th>Estado</th>
                            <th>NIT</th>
                            <th>Contratista</th>
                            <th>Entidad Contratante</th>
                            <th>Fecha inicio</th>
                            <th>Fecha estimada fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->contract_number }}</td>
                                <td class="contract-description">{{ \Illuminate\Support\Str::limit($project->project_description, 100) }}</td>
                                <td>{{ $project->projectStatus->status_name }}</td>
                                <td>{{ $project->nit }}</td>
                                <td>{{ $project->contratista }}</td>
                                <td>{{ $project->entidad_contratante }}</td>
                                <td>{{ $project->project_start_date }}</td>
                                <td>{{ $project->project_estimated_end }}</td>
                                <td style="display: flex; align-items: center;">
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-settings-3-line"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                              
                                            @can('store.purchase')
                                                <a href="{{ route('purchaseorder.save', ['id' => $project->id]) }}"
                                                    class="dropdown-item">Crear órdenes de compra</a>
                                            @endcan
                                            <a href="{{ route('workprogress.index', ['id' => $project->id]) }}"
                                                class="dropdown-item">Avance de obra</a>
                                            <a href="{{ route('purchaseorderproject.get', ['id' => $project->id]) }}"
                                                class="dropdown-item">Ver órdenes de compra</a>
                                            <a href="{{ route('chatbyid.get', ['id' => $project->id]) }}"
                                                class="dropdown-item">Chat del proyecto</a>
                                                <a href="{{ route('consolidated.view', ['id' => $project->id]) }}"
                                                 class="dropdown-item">Ver Consolidado</a>

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
            </div>
            {{ $projects->links(data: ['scrollTo' => false]) }}
        @endif
    </x-table>
</div>

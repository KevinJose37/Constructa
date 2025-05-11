<div>
    <x-page-title title="Tabla de proyectos"></x-page-title>
    <style>
        .text-wrap {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-all;
        }

        .actions-cell {
            display: table-cell;
            vertical-align: middle;
        }

        .actions-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.1rem;
            height: 100%;
        }

        .action-item {
            flex: 1 1 20%;
            text-align: center;
            position: relative;
        }

        .table-container {
            overflow-x: auto;
            height: 500px; 
        }

        .dropdown-menu {
            position: absolute;
            z-index: 1050;
            left: 0;
            top: 100%;
        }
    </style>
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
                    <thead class="table-dark">
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
        <tr wire:key="project-{{ $project->id }}">
            <td class="contract-description text-wrap">{{ \Illuminate\Support\Str::limit($project->project_name, 100) }}</td>
            <td>{{ $project->contract_number }}</td>
            <td class="contract-description text-wrap">{{ \Illuminate\Support\Str::limit($project->project_description, 100) }}</td>
            <td>{{ $project->projectStatus->status_name }}</td>
            <td>{{ $project->nit }}</td>
            <td>{{ $project->contratista }}</td>
            <td>{{ $project->entidad_contratante }}</td>
            <td>{{ $project->project_start_date }}</td>
            <td>{{ $project->project_estimated_end }}</td>
            <td class="actions-cell">
                <div class="actions-content">
                    <div class="action-item">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-settings-3-line"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-menu-end">
                                @can('store.purchase')
                                    <a href="{{ route('purchaseorder.save', ['id' => $project->id]) }}" class="dropdown-item">Crear órdenes de compra</a>
                                @endcan
                                <!--<a href="{{ route('workprogress.index', ['id' => $project->id]) }}" class="dropdown-item">Avance de obra</a>-->
                                <a href="{{ route('purchaseorderproject.get', ['id' => $project->id]) }}" class="dropdown-item">Órdenes de compra</a>
                                <a href="{{ route('chatbyid.get', ['id' => $project->id]) }}" class="dropdown-item">Chat del proyecto</a>
                                <a href="{{ route('consolidated.view', ['id' => $project->id]) }}" class="dropdown-item">Consolidado</a>
                                <a href="{{ route('budget', ['id_presupuesto' => $project->id]) }}" class="dropdown-item">Presupuesto</a>
                                <a href="{{ route('proyecto-real', ['id' => $project->id]) }}" class="dropdown-item">Capítulo real</a>                                </div>
                        </div>
                    </div>
                    <div class="action-item">
                        <livewire:view-users-project :project="$project" :wire:key="'view-' . $project->id"></livewire:view-users-project>
                    </div>
                    @can('delete.project')
                        <div class="action-item">
                            <a href="#" class="text-reset fs-19 px-1 delete-project-btn" wire:click.prevent="destroyAlert({{ $project->id }}, '{{ $project->project_name }}')" wire:key="delete-{{ $project->id }}">
                                <i class="ri-delete-bin-2-line"></i>
                            </a>
                        </div>
                    @endcan
                    @can('update.project')
                        <div class="action-item">
                            <livewire:update-project :project="$project" :wire:key="'update-' . $project->id"></livewire:update-project>
                        </div>
                    @endcan
                </div>
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

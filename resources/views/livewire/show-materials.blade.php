<div>

    <x-table>

        <div class="row g-2 mb-2">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group w-100">
                    <button class="btn btn-primary" wire:click="performSearch">
                        <i class="ri-search-line"></i>
                    </button>
                    <input type="text"
                        name="filter"
                        class="form-control"
                        placeholder="Buscar Material"
                        wire:model.live="search"
                        wire:keydown.enter="performSearch">
                    <button class="btn btn-outline-secondary"
                        id="clear-filter"
                        wire:click="clearSearch">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Botón para crear un capítulo -->
        @can('store.materials')
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMaterialModal">
                Crear material
            </button>
        </div>
        @endcan

        <table class="table table-striped table-centered mb-3">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Unidad de Medida</th>
                    <th>Categoría</th>
                    @can('delete.materials')
                    <th>Acciones</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach ($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->cod }}</td>
                    <td>{{ $material->unit_measurement }}</td>
                    <td>{{ $material->categoryItems->description ?? 'Sin categoría' }}</td>
                    @can('delete.materials')
                    <td>
                        <a href="javascript:void(0);" wire:click="deleteMaterial({{ $material->id }})">
                            <i class="ri-delete-bin-2-line"></i>
                        </a>
                    </td>
                </tr>
                @endcan
                @endforeach
            </tbody>
        </table>
        {{ $materials->links() }}

    </x-table>

    <livewire:create-material-modal />
<div>

    <x-table>

        <div class="row w-100">
            <div class="col-lg-6 w-25 mb-3">
                <div class="input-group">
                    <button class="btn btn-primary" wire:click="performSearch">
                        <i class="ri-search-line"></i>
                    </button>
                    <input
                        type="text"
                        name="filter"
                        class="form-control"
                        placeholder="Buscar Material"
                        wire:model.live="search"
                        wire:keydown.enter="performSearch">
                    <button
                        class="btn"
                        id="clear-filter"
                        wire:click="clearSearch">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Botón para crear un capítulo -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMaterialModal">
                Crear material
            </button>
        </div>
        <!-- Modal para ingresar el nombre del capítulo -->
        <div wire:ignore.self class="modal fade" id="createMaterialModal" tabindex="-1" aria-labelledby="createMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMaterialModalLabel">Nuevo material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($errorMessage)
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif
                
                <div class="mb-3">
                    <input type="text" 
                           class="form-control @error('materialName') is-invalid @enderror" 
                           placeholder="Nombre del material" 
                           wire:model="materialName">
                    @error('materialName') 
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="text" 
                           class="form-control @error('materialCode') is-invalid @enderror" 
                           placeholder="Código de material" 
                           wire:model="materialCode">
                    @error('materialCode') 
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="text" 
                           class="form-control @error('materialUnit') is-invalid @enderror" 
                           placeholder="Unidad de medida" 
                           wire:model="materialUnit">
                    @error('materialUnit') 
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" wire:click="createMaterial">Crear material</button>
            </div>
        </div>
    </div>
</div>

        <table class="table table-striped table-centered mb-3">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Unidad de Medida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->cod }}</td>
                    <td>{{ $material->unit_measurement }}</td>
                    <td>
                        <a href="javascript:void(0);" wire:click="deleteMaterial({{ $material->id }})">
                            <i class="ri-delete-bin-2-line"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $materials->links() }}
    </x-table>
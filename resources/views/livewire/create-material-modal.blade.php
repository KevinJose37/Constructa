<div wire:ignore.self class="modal fade" id="createMaterialModal" tabindex="-1" aria-labelledby="createMaterialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMaterialModalLabel">Nuevo material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errorMessage)
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif

                <div class="mb-3">
                    <input type="text" class="form-control @error('materialName') is-invalid @enderror"
                        placeholder="Nombre del material" wire:model="materialName">
                    @error('materialName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control @error('materialUnit') is-invalid @enderror"
                        placeholder="Unidad de medida" wire:model="materialUnit">
                    @error('materialUnit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <select class="form-select @error('materialCategory') is-invalid @enderror"
                        wire:model="materialCategory">
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->description }} -
                                ({{ $category->prefix }})
                            </option>
                        @endforeach
                    </select>
                    @error('materialCategory')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" wire:click="createMaterial">Crear
                    material</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para el manejo de modales -->
<script>
    document.addEventListener('livewire:initialized', () => {
        // Escuchar el evento 'close-modal' y cerrar el modal
        Livewire.on('close-modal', (modalId) => {
            const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            if (modal) {
                modal.hide();
            }
        });

        // Escuchar el evento 'open-modal' y abrir el modal
        Livewire.on('open-modal', (modalId) => {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        // Prevenir el envío del formulario al presionar Enter en los inputs
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                e.preventDefault();
            }
        });
    });
</script>

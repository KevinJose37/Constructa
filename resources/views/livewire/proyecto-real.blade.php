<div class="card">
    <div class="card-body">
        <div class="table-responsive-sm mb-3">
            <table class="table table-bordered table-centered mb-3">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">PROYECTO REAL DE OBRA.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center">
                            <strong>PROYECTO:</strong> {{ $project->project_name }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Mensaje de éxito -->
            <div x-data="{ show: false }" x-show="show" x-init="Livewire.on('chapterSaved', () => {
                show = true;
                setTimeout(() => show = false, 3000);
            })" class="alert alert-success"
                style="display: none;" id="successMessage">
                ¡Capítulo guardado correctamente!
            </div>

            <!-- Tabla de capítulos e ítems existentes -->
            <div class="table-responsive-sm mb-3">
                <table class="table table-bordered table-centered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#ITEM</th>
                            <th class="text-center">DESCRIPCIÓN</th>
                            <th class="text-center">VR. TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chapters as $chapter)
                            <tr class="table-primary">
                                <td colspan="2">
                                    <strong>Cap. {{ $chapter->chapter_number }}:</strong> {{ $chapter->chapter_name }}
                                </td>
                                <td class="text-end">
                                    <button wire:click="deleteChapter({{ $chapter->id }})" type="button"
                                        class="btn btn-danger btn-sm">
                                        Eliminar Capítulo
                                    </button>
                                </td>
                            </tr>
                            @foreach ($chapter->items as $item)
                                <tr>
                                    <td class="text-center">{{ $item->item_number }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay capítulos registrados aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botón para abrir el modal -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chapterModal">
                    Crear capítulo e ítems
                </button>
            </div>

            <!-- Modal para crear capítulo e ítems -->
            <div wire:ignore.self class="modal fade" id="chapterModal" tabindex="-1"
                aria-labelledby="chapterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="chapterModalLabel">Crear capítulo e ítems</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="saveChapter">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="chapterNumber" class="form-label">Número de capítulo</label>
                                    <input type="text" wire:model="chapter_number" class="form-control"
                                        placeholder="Ingrese el número de capítulo">
                                </div>
                                <div class="mb-3">
                                    <label for="chapterName" class="form-label">Nombre del capítulo</label>
                                    <input type="text" wire:model="chapter_name" class="form-control"
                                        placeholder="Ingrese el nombre del capítulo">
                                </div>

                                <!-- Tabla de ítems -->
                                <div class="table-responsive-sm mt-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#ITEM</th>
                                                <th>DESCRIPCIÓN</th>
                                                <th>VR. TOTAL.</th>
                                                <th width="100">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $index => $item)
                                                <tr wire:key="item-{{ $index }}">
                                                    <td>
                                                        <input type="text"
                                                            wire:model="items.{{ $index }}.item_number"
                                                            class="form-control" placeholder="Ej: 1.1">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            wire:model="items.{{ $index }}.description"
                                                            class="form-control" placeholder="Descripción">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            wire:model="items.{{ $index }}.total"
                                                            class="form-control" placeholder="Total">
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            wire:click="removeItem({{ $index }})"
                                                            class="btn btn-danger btn-sm">
                                                            Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" wire:click.stop="addItem" wire:loading.attr="disabled"
                                    class="btn btn-info mt-2">
                                    <span wire:loading.remove>Añadir ítem</span>
                                    <span wire:loading>Cargando...</span>
                                </button>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Guardar capítulo</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modalElement = document.getElementById('chapterModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });
    </script>
@endpush

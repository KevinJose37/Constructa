<div class="card">
    <div class="card-body">
        <div class="table-responsive-sm mb-3">
            <table class="table table-bordered table-centered mb-3">
                <thead class="table-dark">
                    <tr>
                        <th colspan="12" class="text-center">PROYECTO REAL DE OBRA.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="12" class="text-center">
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
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" colspan="2">#ITEM</th>
                            <th class="text-center" colspan="6">DESCRIPCIÓN</th>
                            <th class="text-center">ALERTA</th>
                            <th class="text-center" colspan="3">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chapters as $chapter)
                            <tr class="table-primary">
                                <td colspan="8">
                                    <strong>Cap. {{ $chapter->chapter_number }}:</strong> {{ $chapter->chapter_name }}
                                </td>
                                <td class="text-end" colspan="4">
                                    <div class="btn-group" role="group">
                                        @can('update.realproject')
                                            <button type="button" class="btn btn-warning btn-sm"
                                                wire:click="editChapter({{ $chapter->id }})" title="Editar capítulo">
                                                <i class="ri-pencil-line"></i> Editar
                                            </button>
                                        @endcan
                                        @can('delete.realproject')
                                            <button wire:click="deleteChapter({{ $chapter->id }})" type="button"
                                                class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-line"></i> Eliminar Capítulo
                                            </button>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                            @php
                                $sumTotal = 0;
                            @endphp
                            {{-- @dd($chapter->workProgressChapters); --}}
                            @foreach ($chapter->items as $item)
                                @php
                                    $sumTotal = bcadd($sumTotal, $item->total, 2);

                                    $hasAlert = false;
                                    $textAlert = '';

                                    $hasAlertFisico = false;
                                    $textAlertFisico = '';

                                    // Obtener el detalle del avance relacionado
                                    $progressDetail = $chapter->workProgressChapters
                                        ? $chapter->workProgressChapters->details->firstWhere('item', $item->id)
                                        : null;


                                    if ($progressDetail) {
                                        // Umbral financiero
                                        $div = $progressDetail->adjusted_value ?? $progressDetail->partial_value;
                                        $avanceFinanciero = ($sumTotal / $div) * 100;

                                        if ($avanceFinanciero > $item->umbral_financiero) {
                                            $hasAlert = true;
                                            $textAlert =
                                                "El avance financiero del ítem {$item->item_number} superó el umbral de {$item->umbral_financiero}%. Avance actual: " .
                                                number_format($avanceFinanciero, 2) .
                                                '%';
                                        }

                                        // Umbral físico
                                        $divFisico =
                                            $progressDetail->adjusted_quantity ?? $progressDetail->contracted_quantity;
                                        $avanceFisico = ($progressDetail->resume_quantity / $divFisico) * 100;

                                        if ($avanceFisico < $item->umbral_fisico) {
                                            $hasAlertFisico = true;
                                            $textAlertFisico =
                                                "El avance físico del ítem {$item->item_number} está por debajo del umbral de {$item->umbral_fisico}%. Avance actual: " .
                                                number_format($avanceFisico, 2) .
                                                '%';
                                        }

                                        // Construir mensaje de alerta combinado
                                        $alertMessage = '';
                                        if ($hasAlert) {
                                            $alertMessage .= $textAlert;
                                        }
                                        if ($hasAlertFisico) {
                                            if ($alertMessage != '') {
                                                $alertMessage .= ' | ';
                                            }
                                            $alertMessage .= $textAlertFisico;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center" colspan="2">{{ $item->item_number }}</td>
                                    <td colspan="6">{{ $item->description }}</td>
                                    <td>
                                        @if (!empty($alertMessage))
                                            <span class="text-danger" title="{{ $alertMessage }}">
                                                <i class="ri-error-warning-line"></i> {{ $alertMessage }}
                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>
                                    <td colspan="3" class="text-end">
                                        <button wire:click="viewInfoItem({{ $item->id }}, {{ $chapter->id }})"
                                            type="button" class="btn btn-info btn-sm">
                                            <i class="ri-eye-line"></i> Ver información item
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="8" class="text-end"><strong>Subtotal del Capítulo:</strong></td>
                                <td class="text-end" colspan="4">
                                    <strong>{{ number_format($sumTotal, 2) }} $</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No hay capítulos registrados aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botón para abrir el modal -->
            @can('store.realproject')
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chapterModal">
                        <i class="ri-add-line"></i> Crear capítulo e ítems
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
                                                    <th>UMBRAL FÍSICO (%)</th>
                                                    <th>UMBRAL FINANCIERO (%)</th>
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
                                                            <input type="text"
                                                                wire:model="items.{{ $index }}.umbral_fisico"
                                                                class="form-control" placeholder="Porcentaje %">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                wire:model="items.{{ $index }}.umbral_financiero"
                                                                class="form-control" placeholder="Porcentaje %">
                                                        </td>
                                                        <td x-data>
                                                            <input type="text"
                                                                x-on:input="$el.value = $el.value 
            ? new Intl.NumberFormat('es-CO').format($el.value.replace(/\./g, '').replace(',', '.')) 
            : ''"
                                                                wire:model.defer="items.{{ $index }}.total"
                                                                class="form-control text-end" placeholder="Total">
                                                            @error("items.$index.total")
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                wire:click="removeItem({{ $index }})"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <button type="button" wire:click.stop="addItem" wire:loading.attr="disabled"
                                        class="btn btn-info mt-2">
                                        <span wire:loading.remove><i class="ri-add-line"></i>Añadir ítem</span>
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
            @endcan
        </div>
    </div>
    @can('update.realproject')
        <!-- Modal para editar capítulo -->
        <div wire:ignore.self class="modal fade" id="editChapterModal" tabindex="-1"
            aria-labelledby="editChapterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="editChapterModalLabel">
                            <i class="ri-pencil-line"></i> Editar capítulo e ítems
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updateChapter">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editChapterNumber" class="form-label">Número de capítulo</label>
                                <input type="text" class="form-control"
                                    wire:model.defer="editCapitulo.numero_capitulo"
                                    placeholder="Ingrese el número de capítulo">
                                @error('editCapitulo.numero_capitulo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="editChapterName" class="form-label">Nombre del capítulo</label>
                                <input type="text" class="form-control"
                                    wire:model.defer="editCapitulo.nombre_capitulo"
                                    placeholder="Ingrese el nombre del capítulo">
                                @error('editCapitulo.nombre_capitulo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tabla de ítems para edición -->
                            <div class="table-responsive-sm mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#ITEM</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>UMBRAL FÍSICO (%)</th>
                                            <th>UMBRAL FINANCIERO (%)</th>
                                            {{-- <th>VR. UNIT.</th> --}}
                                            <th width="100">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($editItems as $index => $item)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        wire:model.defer="editItems.{{ $index }}.item_number"
                                                        placeholder="Ej: 1.1">
                                                    @error("editItems.$index.item_number")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        wire:model.defer="editItems.{{ $index }}.description"
                                                        placeholder="Descripción">
                                                    @error("editItems.$index.description")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        wire:model.defer="editItems.{{ $index }}.umbral_fisico"
                                                        placeholder="Porcentaje %">
                                                    @error("editItems.$index.umbral_fisico")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        wire:model.defer="editItems.{{ $index }}.umbral_financiero"
                                                        placeholder="Porcentaje %">
                                                    @error("editItems.$index.umbral_financiero")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>


                                                {{-- <td>
                                                <input type="number" step="0.01" class="form-control"
                                                    wire:model.defer="editItems.{{ $index }}.total"
                                        placeholder="0.00">
                                        @error("editItems.$index.total")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        </td> --}}
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        wire:click.prevent="removeEditItem({{ $index }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-info mt-2" wire:click.prevent="addEditItem">
                                <i class="ri-add-line"></i> Añadir ítem
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                Actualizar capítulo
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="cancelEdit">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan


    <!-- Modal para visualizar materiales -->
    <div wire:ignore.self class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-sm">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="itemsModalLabel">
                        Materiales
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped align-middle text-center mb-0">
                            <thead class="table-info">
                                <tr>
                                    <th scope="col">Orden de compra</th>
                                    <th scope="col">N° Item</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">UND</th>
                                    <th scope="col">Valor total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($currentItemsRedirect as $item)
                                    <tr>
                                        <td>{{ $item->purchase_order_id ?? 'N/A' }}</td>
                                        <td>{{ $item->invoiceDetail->id_item ?? 'N/A' }}</td>
                                        <td class="text-start">{{ $item->invoiceDetail->item->name ?? 'Sin nombre' }}
                                        </td>
                                        <td>{{ number_format($item->invoiceDetail->quantity ?? 0, 2) }}</td>
                                        <td>{{ $item->invoiceDetail->item->unit_measurement ?? '-' }}</td>
                                        <td>${{ number_format($item->invoiceDetail->total_price_iva ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted text-center py-3">No hay materiales
                                            asociados a este ítem.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
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
@endpush

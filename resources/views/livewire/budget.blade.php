@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive-sm mb-3">
            <table class="table table-bordered table-centered mb-3">
                <thead>
                    <tr>
                        <th colspan="5" class="text-center">PRESUPUESTO GENERAL DE OBRA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center">
                            <strong>OBRA:</strong> {{ $budget->descripcion_obra }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <strong>LOCALIZACIÓN:</strong>
                            @if ($budget->localizacion === 'Por definir')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#localizacionModal">
                                Asignar Localización
                            </button>
                            @else
                            {{ $budget->localizacion }}
                            @endif
                        </td>
                        <td colspan="2"><strong>FECHA:</strong> {{ $budget->fecha }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabla principal de capítulos e ítems -->
            <div class="table-responsive-sm mb-3">
                <table class="table table-bordered table-centered mb-0">
                    <thead>
    <tr>
        <th class="text-center">#ITEM</th>
        <th class="text-center">DESCRIPCIÓN</th>
        <th class="text-center">UND</th>
        <th class="text-center">CANTIDAD</th>
        <th class="text-center">VR. UNIT.</th>
        <th class="text-center">VR. TOTAL</th>
    </tr>
</thead>
<tbody>
    @foreach($capitulos as $capitulo)
    <tr class="table-primary">
        <td colspan="6">
            <strong>Cap. {{ $capitulo->numero_capitulo }}:</strong> {{ $capitulo->nombre_capitulo }}
        </td>
    </tr>

                        @php
                        $subtotal = 0; // Inicializar el subtotal
                        @endphp
                        @foreach($capitulo->items as $item)
                        @php
                        $subtotal += $item->vr_total; // Acumular el valor total de cada ítem
                        @endphp
                        <tr>
                        <td class="text-center">{{ $item->numero_item }}</td>
                        <td>{{ $item->descripcion }}</td>
                            <td class="text-center">{{ $item->und }}</td>
                            <td class="text-end">{{ number_format($item->cantidad, 2) }}</td>
                            <td class="text-end">{{ number_format($item->vr_unit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->vr_total, 0) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="5" class="text-end"><strong>Subtotal del Capítulo:</strong></td>
                            <td class="text-end"><strong>{{ number_format($subtotal, 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Botón para abrir el modal -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chapterModal">
                    Crear capítulo e items
                </button>
            </div>

            <div wire:ignore.self class="modal fade" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chapterModalLabel">Crear capítulo e ítems</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="saveChapter">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="chapterNumber" class="form-label">Número de capítulo</label>
                        <input type="text" class="form-control" wire:model.defer="modalCapitulo.numero_capitulo" placeholder="Ingrese el número de capítulo">
                        @error('modalCapitulo.numero_capitulo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="chapterName" class="form-label">Nombre del capítulo</label>
                        <input type="text" class="form-control" wire:model.defer="modalCapitulo.nombre_capitulo" placeholder="Ingrese el nombre del capítulo">
                        @error('modalCapitulo.nombre_capitulo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tabla de ítems -->
                    <div class="table-responsive-sm mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#ITEM</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>UND</th>
                                    <th>CANTIDAD</th>
                                    <th>VR. UNIT.</th>
                                    <th width="100">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modalItems as $index => $item)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" wire:model.defer="modalItems.{{$index}}.numero_item" placeholder="Ej: 1.1">
                                        @error("modalItems.$index.numero_item") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model.defer="modalItems.{{$index}}.descripcion" placeholder="Descripción">
                                        @error("modalItems.$index.descripcion") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model.defer="modalItems.{{$index}}.und" placeholder="UND">
                                        @error("modalItems.$index.und") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="modalItems.{{$index}}.cantidad" placeholder="0.00">
                                        @error("modalItems.$index.cantidad") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="modalItems.{{$index}}.vr_unit" placeholder="0.00">
                                        @error("modalItems.$index.vr_unit") <span class="text-danger">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" wire:click.prevent="removeItem({{$index}})">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-info mt-2" wire:click.prevent="addItem">
                        Añadir ítem
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar capítulo</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div>

        <!-- Modal de Localización -->
<div class="modal fade" id="localizacionModal" tabindex="-1" aria-labelledby="localizacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="localizacionModalLabel">Asignar Localización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="updateLocalizacion">
                    <div class="mb-3">
                        <label for="localizacion" class="form-label">Localización</label>
                        <input type="text" class="form-control" wire:model="localizacion">
                        @error('localizacion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Scripts para el manejo del modal -->
        <script>
    document.addEventListener('livewire:initialized', () => {
        // Escuchar el evento 'close-modal' y cerrar el modal
        Livewire.on('close-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('localizacionModal'));
            if (modal) {
                modal.hide();
            }
        });
    });
</script>

        
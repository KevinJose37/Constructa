<div>
    <x-page-title title="Progreso de obra"></x-page-title>

    <!-- Botón para crear un capítulo -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChapterModal">
            Crear capítulo
        </button>
    </div>

    <!-- Modal para ingresar el nombre del capítulo -->
    <div wire:ignore.self class="modal fade" id="createChapterModal" tabindex="-1" aria-labelledby="createChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createChapterModalLabel">Nuevo Capítulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Nombre del capítulo" wire:model="chapterName">
                    @error('chapterName') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="createChapter" data-bs-dismiss="modal">Crear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mostrar los capítulos creados -->
    @foreach($chapters as $chapter)
        <div class="table-title">
        {{ $chapter['chapter_number'] }}.{{ $chapter['chapter_name'] }} 
        </div>

        <x-table>
            <div class="table-responsive">
                <table class="table table-striped table-centered mb-0">
                    <thead>
                        <tr>
                            <!-- Usamos colspan para que el título abarque las columnas deseadas -->
                            <th colspan="2" class="text-center"></th>
                            <th colspan="4" class="text-center">Condiciones contratadas</th>
                            <th colspan="3" class="text-center">Balance mayores y menores</th>
                            <th colspan="2" class="text-center">Cantidades ajustadas balance</th>
                            <th colspan="2" class="text-center">Avance semana</th>
                            <th colspan="4" class="text-center">Resumen</th>
                        </tr>
                        <tr>
                            <th>Items</th>
                            <th class="border-right">Descripcion</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Valor Unitario</th>
                            <th class="border-right">Valor Parcial</th>
                            <th>+ O -</th>
                            <th>Cantidad</th>
                            <th class="border-right">Valor Total</th>
                            <th>Cantidad</th>
                            <th class="border-right">Valor Total</th>
                            <th>Cantidad</th>
                            <th class="border-right">Valor Total</th>
                            <th>Total Cantidad</th>
                            <th>Saldo a ejecutar</th>
                            <th>Valor ejecutado</th>
                            <th class="border-right">% ejecutado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se llenarán las filas dinámicamente -->
                    </tbody>
                </table>
            </div>
        </x-table>
    @endforeach

    <style>
        .table-title {
            margin-bottom: 0px;
            padding: 10px 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            text-align: left;
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            border: 1px solid #ddd;
        }

        .border-right {
            border-right: 1px solid gray;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</div>

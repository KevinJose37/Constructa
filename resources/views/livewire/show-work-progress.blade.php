<div>
    <x-page-title title="Progreso de obra"></x-page-title>
    <div class="row mb-2">
        {{-- Chips de semanas seleccionadas --}}
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                @foreach ($filterWeeks as $selectedWeekId)
                    @php
                        $selectedWeek = $weeks->firstWhere('id', $selectedWeekId);
                    @endphp
                    @if ($selectedWeek)
                        <span class="badge bg-primary d-flex align-items-center">
                            Semana {{ $selectedWeek->number_week }} - {{ $selectedWeek->string_date }}
                            <button type="button" wire:click="removeWeek({{ $selectedWeek->id }})"
                                class="btn-close btn-close-white btn-sm ms-2" aria-label="Eliminar"
                                style="font-size: 0.6rem;"></button>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
    <div class="row align-items-center mb-3 g-2">
        {{-- Select de semanas y botón filtrar --}}
        <div class="col-auto">
            <div class="input-group">
                <button type="button" class="btn btn-warning" id="filter-week-btn">
                    <i class="ri-search-line me-1"></i> Filtrar semana
                </button>
                <select id="filter-week" class="form-select" wire:model.live="selectedWeek" style="min-width: 200px;">
                    <option value="">Selecciona una semana</option>
                    @foreach ($weeks as $week)
                        @if (!in_array($week->id, $filterWeeks))
                            <option value="{{ $week->id }}"> Semana {{ $week->number_week }} -
                                {{ $week->string_date }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Botón crear semana --}}
        <div class="col-auto">
            <div class="d-flex align-items-center">
                <livewire:work-progress-week :projectId="$projectId" />
            </div>
        </div>

        {{-- Botón exportar Excel --}}
        <div class="col-auto">
            <button type="button" class="btn btn-success">
                <i class="ri-file-excel-line"></i> Exportar Excel
            </button>
        </div>
    </div>
    @foreach ($chapters as $chapter)
        @if ($chapter->workProgressChapter && $chapter->workProgressChapter->details)
            <div class="table-title">
                {{ $chapter->workProgressChapter->chapter_number }}.{{ $chapter->workProgressChapter->chapter_name }}
            </div>
            <x-table>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <!-- Usamos colspan para que el título abarque las columnas deseadas -->
                                    <th colspan="2" class="fixed-column text-center"></th>
                                    <th colspan="4" class="text-center">Condiciones contratadas</th>
                                    <th colspan="3" class="text-center">Balance mayores y menores</th>
                                    <th colspan="2" class="text-center">Cantidades ajustadas balance</th>
                                    <th colspan="{{ $filterWeeks ? '4' : '1' }}" class="text-center">Avance semana</th>
                                    <th colspan="1" class="text-center">Evidencias</th>
                                    <th colspan="4" class="text-center">Resumen</th>
                                </tr>
                                <tr>
                                    <th class="fixed-column">Items</th>
                                    <th class="fixed-column border-right">Descripcion</th>
                                    <th>Unidad</th>
                                    <th>Cantidad</th>
                                    <th>Valor Unitario</th>
                                    <th class="border-right">Valor Total</th>
                                    <th>Cantidad</th>
                                    <th>Valor Total</th>
                                    <th class="border-right"></th>
                                    <th>Cantidad</th>
                                    <th class="border-right">Valor Total</th>
                                    @if ($filterWeeks)
                                        <th>Cantidad</th>
                                        <th>Valor total</th>
                                        <th>% Repr.</th>
                                    @endif
                                    {{-- Avance semana --}}
                                    <th class="border-right"></th>
                                    {{-- Evidencias --}}
                                    <th class="border-right"></th>
                                    <th>Total Cantidad</th>
                                    <th>Saldo a ejecutar</th>
                                    <th>Valor ejecutado</th>
                                    <th class="border-right">% ejecutado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chapter->workProgressChapter->details as $detail)
                                    <tr>
                                        <!-- Columnas fijas -->
                                        <td class="fixed-column">{{ $detail->item }}</td>
                                        <td class="fixed-column">{{ $detail->description }}</td>

                                        <!-- Columnas desplazables -->
                                        <td>{{ $detail->unit }}</td>
                                        <td>{{ number_format($detail->contracted_quantity, 0) }}</td>
                                        <td>${{ number_format($detail->unit_value, 2) }}</td>
                                        <td>${{ number_format($detail->partial_value, 2) }}</td>
                                        {{-- Balance de mayores y menores --}}

                                        {{-- Verificamos si ya existen un balance y de qué tipo --}}
                                        @if ($detail->balance_quantity && $detail->balance_adjustment)
                                            {{-- Cantidad --}}
                                            <td
                                                class="text-center text-{{ $detail->balance_adjustment == 'up' ? 'success' : 'danger' }}">
                                                <i class="ri-arrow-{{ $detail->balance_adjustment }}-line">
                                                    {{ number_format($detail->balance_quantity, 0) }}
                                            </td>
                                            {{-- Valor total --}}
                                            <td class="text-center">
                                                $ {{ number_format($detail->balance_value, 2) }}</td>
                                        @else
                                            <td>{{ number_format($detail->balance_quantity, 0) ?? '-' }}</td>
                                            <td>$ {{ number_format($detail->balance_value, 2) ?? '-' }}</td>
                                        @endif

                                        <td>
                                            <livewire:balance-workprogress :detail="$detail" :workProgress="$chapter->workProgressChapter"
                                                :wire:key="'balance-'.$detail->id" />
                                        </td>

                                        {{--  --}}
                                        <td>{{ number_format($detail->adjusted_quantity, 0) ?? '-' }}</td>
                                        <td>$ {{ number_format($detail->adjusted_value, 2) ?? '-' }}</td>
                                        @if ($filterWeeks)
                                            <td>
                                                {{ number_format($detail->executed_quantity_sum, 0) ?? '-' }}
                                            </td>
                                            <td>$
                                                {{ number_format($detail->executed_total_sum, 2) ?? '-' }}
                                            </td>
                                            <td>{{ $detail->execute_percentage_sum ?? '-' }}%
                                            </td>
                                        @endif
                                        <td>
                                            <livewire:progress-week :detail="$detail" :workProgress="$chapter->workProgressChapter"
                                                :week="$filterWeeks"
                                                :wire:key="'progress-'.$detail->id.md5(serialize($filterWeeks))" />
                                        </td>
                                        <td>
                                            <livewire:weekly-progress-images :detail="$detail" :workProgress="$chapter->workProgressChapter"
                                                :filterWeeks="$filterWeeks"
                                                :wire:key="'images-progress-'.$detail->id.md5(serialize($filterWeeks))" />
                                        </td>
                                        <td>{{ number_format($detail->resume_quantity, 0) ?? '-' }}</td>
                                        <td>
                                            @if ($detail->resume_execute_value < 0)
                                                <span class="badge bg-danger">Se superó la cantidad contratada</span>
                                            @else
                                                ${{ number_format($detail->resume_execute_value, 2) ?? '-' }}
                                            @endif
                                        </td>

                                        <td>${{ number_format($detail->resume_value, 2) ?? '-' }}</td>
                                        <td>{{ $detail->resume_execute_percentage ?? '-' }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-table>
        @endif
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

        /* Estilos para las columnas fijas */
        .table-container {
            position: relative;
            overflow-x: auto;
            width: 100%;
        }

        .fixed-column {
            position: sticky;
            left: 0;
            background-color: white;
            z-index: 1;
        }

        /* Asegurar que la segunda columna fija esté al lado de la primera */
        th.fixed-column:nth-child(2),
        td.fixed-column:nth-child(2) {
            left: 50px;
            /* Ajusta este valor según el ancho de tu primera columna */
        }

        /* Estilo para el thead fijo */
        thead th.fixed-column {
            z-index: 2;
            background-color: #f8f9fa;
            /* Color de fondo del header */
        }

        th.fixed-column,
        td.fixed-column {
            width: 50px;
            min-width: 50px;
            max-width: 70px;
            word-wrap: break-word;
        }

        th.fixed-column:nth-child(2),
        td.fixed-column:nth-child(2) {
            width: 200px;
            /* Ancho más grande para la descripción */
            min-width: 200px;
            max-width: 200px;
        }
    </style>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('#filter-week');
            select.style.display = 'none';
            document.querySelector('#filter-week-btn').addEventListener('click', function() {
                select.style.display = (select.style.display === 'none') ? 'block' : 'none';
            });

        });
    </script>
@endpush

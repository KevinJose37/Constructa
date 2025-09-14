<div>
    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
        <x-page-title title="Progreso de obra"></x-page-title>
        @if ($project)
            <span class="badge bg-primary text-white fs-6 shadow-sm px-3 py-2 rounded-pill"
                style="font-weight: 600; letter-spacing: 0.05em;">
                Proyecto: {{ $project->project_name }}
            </span>
        @endif
    </div>
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
        @can('report.progress')
            <div class="col-auto">
                <button type="button" class="btn btn-success" wire:click="exportExcel">
                    <i class="ri-file-excel-line"></i> Exportar Excel
                </button>
            </div>
            <div class="col-auto">
                <a href="{{ route('printable.report', ['projectId' => $projectId]) }}{{ $filterWeeks ? '?filterWeeks[]=' . implode('&filterWeeks[]=', $filterWeeks) : '' }}"
                    target="_blank" class="btn btn-danger">
                    <i class="ri-file-pdf-line"></i> Informe
                </a>
            </div>
        @endcan


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
                            <thead class="table-dark">
                                <tr>
                                    <!-- Usamos colspan para que el título abarque las columnas deseadas -->
                                    <th colspan="2" class="fixed-column text-center"></th>
                                    <th colspan="4" class="text-center">Condiciones contratadas</th>
                                    @can('balance.progress')
                                        <th colspan="3" class="text-center">Balance mayores y menores</th>
                                    @endcan
                                    <th colspan="2" class="text-center">Cantidades ajustadas balance</th>
                                    @can('weekly.progress')
                                        <th colspan="{{ $filterWeeks ? '4' : '1' }}" class="text-center">Avance semana</th>
                                    @endcan

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

                                        @can('balance.progress')
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
                                        @endcan

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
                                        {{-- Avance semana --}}
                                        @can('weekly.progress')
                                            <td>
                                                <livewire:progress-week :detail="$detail" :workProgress="$chapter->workProgressChapter"
                                                    :week="$filterWeeks"
                                                    :wire:key="'progress-'.$detail->id.md5(serialize($filterWeeks))" />
                                            </td>
                                        @endcan
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
            color: #49526b;
            border: 1px solid #ddd;
        }

   /* Primera columna (Items) */
td.fixed-column:first-child,
th.fixed-column:first-child {
    position: sticky;
    left: 0;
    z-index: 2;
    width: 100px;
    min-width: 100px;
    max-width: 100px;
    background-color: #fff; /* fallback */
}

/* Segunda columna (Descripción) */
td.fixed-column:nth-of-type(2),
th.fixed-column:nth-of-type(2) {
    position: sticky;
    left: 100px; /* ancho de la primera */
    z-index: 2;
    width: 220px;
    min-width: 220px;
    max-width: 220px;
    background-color: #fff; /* fallback */
}

/* Colores según stripe */
.table-striped tbody tr:nth-of-type(odd) td.fixed-column {
    background-color: #ffffff !important; /* filas blancas */
}

.table-striped tbody tr:nth-of-type(even) td.fixed-column {
    background-color: #ffffffff !important; /* filas grises de Bootstrap */
}

/* Encabezado sticky */
thead th.fixed-column {
    z-index: 3;
    background-color: #49526b !important; /* tu color de header */
    color: #fff;
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

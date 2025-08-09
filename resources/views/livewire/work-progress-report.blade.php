<div>
    <div class="container my-4">
        <h1 class="text-center mb-4">INFORME DE OBRA</h1>

        <div class="text-end mb-3 d-print-none">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="ri-printer-line me-1"></i> Imprimir
            </button>
        </div>

        @foreach ($chapters as $chapter)
            @if ($chapter->workProgressChapter && $chapter->workProgressChapter->details)
                <div class="mb-5 border rounded p-3">
                    <div class="bg-secondary text-white p-3 rounded">
                        <h5 class="mb-0">
                            <span class="badge bg-dark me-2">
                                {{ $chapter->workProgressChapter->chapter_number }}
                            </span>
                            {{ $chapter->workProgressChapter->chapter_name }}
                        </h5>
                    </div>

                    @foreach ($chapter->workProgressChapter->details as $detail)
                        <div class="mt-4 border rounded p-3">
                            <div class="mb-3">
                                <h6 class="mb-1">
                                    <span class="badge bg-primary me-2">{{ $detail->item }}</span>
                                    {{ $detail->description }}
                                </h6>
                            </div>

                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>ÍTEM</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th>UNIDAD</th>
                                        <th>CANTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $detail->item }}</td>
                                        <td>{{ $detail->description }}</td>
                                        <td>{{ $detail->unit }}</td>
                                        <td>{{ number_format($detail->contracted_quantity, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @php
                                $imagesGrouped = $detail->weeklyProgressImages->groupBy('week.string_date');
                            @endphp

                            @if ($imagesGrouped->count())
                                <div class="mt-3">
                                    <strong class="d-block mb-2">Evidencias Fotográficas</strong>
                                    @foreach ($imagesGrouped as $weekDate => $images)
                                        <div class="mb-4 border rounded p-2" style="page-break-inside: avoid;">
                                            <small class="text-muted d-block mb-2">
                                                Semana {{ $images->first()->week->number_week }} - {{ $weekDate }}
                                            </small>
                                            <div class="image-row" style="font-size: 0;">

                                                @foreach ($images as $image)
                                                    <div class="image-item d-inline-block border rounded m-1 p-1"
                                                        style="width: 160px; page-break-inside: avoid; vertical-align: top;">
                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                            alt="Evidencia ítem {{ $detail->item }}" class="img-fluid"
                                                            style="max-height: 140px; object-fit: contain; display: block; width: 100%;">
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-3 border rounded bg-light">
                                    <i class="ri-image-line" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No hay evidencias registradas para este ítem</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>

    <style>
        @media print {
            .image-row {
                display: block !important;
            }

            .image-item {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                display: inline-block !important;
            }

            img {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>

</div>

<div>
    <div class="container my-4">
        <h1 class="text-center mb-4">INFORME DE OBRA</h1>

        <div class="d-flex justify-content-end mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="ri-printer-line me-1"></i> Imprimir
            </button>
        </div>

        <div id="informeContenido">
            @foreach ($chapters as $chapter)
                @if ($chapter->workProgressChapter && $chapter->workProgressChapter->details)
                    <div class="card mb-5 chapter-card">
                        <div class="card-header chapter-header">
                            <div class="d-flex align-items-center">
                                <div class="chapter-badge">
                                    <span
                                        class="badge bg-dark fs-5">{{ $chapter->workProgressChapter->chapter_number }}</span>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0 chapter-title">{{ $chapter->workProgressChapter->chapter_name }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            @foreach ($chapter->workProgressChapter->details as $detail)
                                <div class="card mb-4 item-card">
                                    <div class="card-header item-header">
                                        <div class="d-flex align-items-center">
                                            <div class="item-badge">
                                                <span class="badge bg-primary fs-6">{{ $detail->item }}</span>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-0 item-description">{{ $detail->description }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive mb-3">
                                            <table class="table table-striped table-centered mb-0">
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
                                                        <td><span class="fw-bold">{{ $detail->item }}</span></td>
                                                        <td>{{ $detail->description }}</td>
                                                        <td><span class="badge bg-secondary">{{ $detail->unit }}</span>
                                                        </td>
                                                        <td><span
                                                                class="fw-bold text-primary">{{ number_format($detail->contracted_quantity, 0) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        @php
                                            $imagesGrouped = $detail->weeklyProgressImages->groupBy('week.string_date');
                                        @endphp

                                        @if ($imagesGrouped->count())
                                            <div class="evidencias-section">
                                                <h6 class="text-muted mb-3">
                                                    <i class="ri-camera-line me-2"></i>Evidencias Fotográficas
                                                </h6>
                                                @foreach ($imagesGrouped as $weekDate => $images)
                                                    <div class="card-body evidencias-semana mb-3">
                                                        <h6 class="semana-title">
                                                            <i class="ri-calendar-line me-2"></i>
                                                            Semana {{ $images->first()->week->number_week }} -
                                                            {{ $weekDate }}
                                                        </h6>
                                                        <div class="photo-grid">
                                                            @foreach ($images as $image)
                                                                <div class="image-container">
                                                                    <a href="{{ Storage::url($image->image_path) }}"
                                                                        target="_blank" class="image-link">
                                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                                            alt="Evidencia ítem {{ $detail->item }}">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="card-body no-evidencias text-center">
                                                <i class="ri-image-line text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0 mt-2">No hay evidencias registradas para este
                                                    ítem</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <style>
        /* Estilos para capítulos */
        .chapter-card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .chapter-header {
            background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
            border-bottom: none;
            padding: 20px 25px;
        }

        .chapter-badge .badge {
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .chapter-title {
            color: white;
            font-weight: 600;
            font-size: 1.4rem;
        }

        /* Estilos para items */
        .item-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .item-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
        }

        .item-badge .badge {
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .item-description {
            color: #495057;
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* Estilos para la tabla */
        .table th {
            background-color: #e9ecef;
            font-weight: 600;
            color: #495057;
            border-top: none;
        }

        .table td {
            vertical-align: middle;
        }

        /* Sección de evidencias */
        .evidencias-section {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }

        .evidencias-semana {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
        }

        .semana-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        /* Grid de fotos */
        .photo-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
            align-items: flex-start;
        }

        /* Contenedor individual de cada imagen */
        .image-container {
            flex: 0 0 auto;
            max-width: 200px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .image-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Link de la imagen */
        .image-link {
            display: block;
            text-decoration: none;
            padding: 8px;
            background-color: white;
        }

        /* Estilos de la imagen */
        .photo-grid img {
            width: 100%;
            height: auto;
            max-height: 160px;
            object-fit: contain;
            border-radius: 4px;
            background-color: #f8f9fa;
            transition: opacity 0.2s ease;
        }

        .image-link:hover img {
            opacity: 0.9;
        }

        /* Contenedor para cuando no hay evidencias */
        .no-evidencias {
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 30px;
        }

        /* Responsive design */
        @media (max-width: 768px) {

            .chapter-header,
            .item-header {
                padding: 15px;
            }

            .chapter-title {
                font-size: 1.2rem;
            }

            .item-description {
                font-size: 1rem;
            }

            .photo-grid {
                gap: 12px;
            }

            .image-container {
                max-width: 150px;
            }

            .photo-grid img {
                max-height: 120px;
            }
        }

        @media (max-width: 480px) {
            .photo-grid {
                justify-content: center;
            }

            .image-container {
                max-width: 120px;
            }

            .photo-grid img {
                max-height: 100px;
            }
        }

        /* Estilos para impresión */
        @media print {
            button {
                display: none !important;
            }


            .chapter-card,
            .item-card {
                box-shadow: none;
                border: 1px solid #000;
                page-break-inside: avoid;
            }

            .chapter-header {
                background: #6c757d !important;
                -webkit-print-color-adjust: exact;
            }

            .image-container {
                box-shadow: none;
                border: 1px solid #000;
            }

            .image-container:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>

</div>

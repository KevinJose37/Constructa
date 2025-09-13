<div>
    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
        <x-page-title title="Tabla de consolidado"></x-page-title>
        @if ($project)
            <span class="badge bg-primary text-white fs-6 shadow-sm px-3 py-2 rounded-pill"
                style="font-weight: 600; letter-spacing: 0.05em;">
                Proyecto: {{ $project->project_name }}
            </span>
        @endif
    </div>


    <x-table>
        <!-- Fila para el filtro de búsqueda: centrado y con tamaño optimizado -->
        <div class="row mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="input-group">
                    <button class="btn btn-primary" type="button" aria-label="Buscar">
                        <i class="ri-search-line"></i>
                    </button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar material"
                        wire:model.live="search" aria-label="Buscar material">
                    <button class="btn btn-danger" id="clear-filter" wire:click="$set('search', '')" type="button"
                        aria-label="Limpiar búsqueda">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla responsive con scroll horizontal -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-centered mb-0 align-middle">
                <thead class="table-dark text-nowrap">
                    <tr>
                        <th>ORDEN DE COMPRA</th>
                        <th>ITEM</th>
                        <th>DESCRIPCIÓN</th>
                        <th>UND.</th>
                        <th>CANTIDAD</th>
                        <th>VALOR UNITARIO SIN IVA</th>
                        <th>VALOR PARCIAL ANTES DE IVA</th>
                        <th>IVA (%)</th>
                        <th>VALOR UNITARIO INCLUIDO IVA</th>
                        <th>VALOR PARCIAL INCLUIDO IVA</th>
                        <th>RETENCIÓN (%)</th>
                        <th>VALOR RETENCIÓN</th>
                        <th>VALOR TOTAL INCLUIDO IVA Y RETENCIÓN</th>
                        <th>EMPRESA</th>
                        <th>TELÉFONO DE CONTACTO</th>
                        <th>DESTINO DE MATERIAL</th>
                        <th>FORMA DE PAGO</th>
                        <th>CUENTA BANCARIA</th>
                        <th>TIPO SOPORTE</th>
                        <th>Tiene Soporte?</th>
                        <th>Fecha de Pago</th>
                        <th>Quién Pagó?</th>
                        <th>Es Caja Menor?</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @foreach ($purchaseOrder as $order)
                        @php $itemCounter = 1; @endphp
                        @foreach ($order->invoiceDetails as $detail)
                            @php
                                $retentionPercentage = 2.5;
                                $retentionValue = $detail->total_price * ($retentionPercentage / 100);
                                $totalWithRetention = $detail->total_price_iva - $retentionValue;
                            @endphp
                            <tr>
                                <td>{{ $detail->id_purchase_order }}</td>
                                <td>{{ $itemCounter++ }}</td>
                                <td>{{ $detail->item->name }}</td>
                                <td>{{ $detail->item->unit_measurement }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>${{ number_format($detail->price, 2, '.', ',') }}</td>
                                <td>${{ number_format($detail->total_price, 2, '.', ',') }}</td>
                                <td>{{ $detail->iva }}</td>
                                <td>${{ number_format($detail->price_iva, 2, '.', ',') }}</td>
                                <td>${{ number_format($detail->total_price_iva, 2, '.', ',') }}</td>
                                <td>{{ $retentionPercentage }}%</td>
                                <td>${{ number_format($retentionValue, 2, '.', ',') }}</td>
                                <td>${{ number_format($totalWithRetention, 2, '.', ',') }}</td>
                                <td>{{ $order->company_name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->material_destination }}</td>
                                <td>{{ $order->paymentMethod->payment_name }}</td>
                                <td>{{ $order->bank_name }} - {{ $order->account_type }} -
                                    {{ $order->account_number }}</td>
                                <td>{{ $order->supportType->support_name }}</td>
                                <td>{{ $order->has_support ? 'Sí' : 'No' }}</td>
                                <td>{{ $order->payment_date }}</td>
                                <td>{{ $order->payer }}</td>
                                <td>{{ $order->is_petty_cash ? 'Sí' : 'No' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales alineados a la derecha, con espacio y responsive -->
        <div class="d-flex justify-content-end flex-column flex-sm-row gap-3 mt-3">
            <h5 class="mb-0">
                Total órdenes de compra:
                <span class="badge bg-primary fs-5">
                    ${{ number_format($totalPayable, 2, '.', ',') }}
                </span>
            </h5>
            <h5 class="mb-0">
                Valor parcial incluido IVA:
                <span class="badge bg-success fs-5">
                    ${{ number_format($totalPriceIva, 2, '.', ',') }}
                </span>
            </h5>
        </div>


        <!-- Paginación centrada -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
        </div>
    </x-table>
</div>

<div>
    <x-page-title title="Tabla de consolidado"></x-page-title>
    <x-table>
        <div class="row w-100 w-25">
            <div class="col-lg-6 w-25">
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar material" wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i class="ri-close-line"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive" >
            <table class="table table-striped table-centered mb-0">
                <thead class="table-dark">
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
                        <th>RETENCIÓN (%)</th> <!-- Nueva columna -->
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
                <tbody>
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
                                <td>{{ $retentionPercentage }}%</td> <!-- Muestra el porcentaje de retención -->
                                <td>${{ number_format($retentionValue, 2, '.', ',') }}</td>
                                <td>${{ number_format($totalWithRetention, 2, '.', ',') }}</td>
                                <td>{{ $order->company_name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->material_destination }}</td>
                                <td>{{ $order->paymentMethod->payment_name }}</td>
                                <td>{{ $order->bank_name }} - {{ $order->account_type }} - {{ $order->account_number }}</td>
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
            <div class="text-end mt-3">
                <div class="float-end mt-3 mt-sm-0">
                    <h4>Total órdenes de compra: ${{ number_format($totalPayable, 2, '.', ',') }}</h4>
                    <h4>Valor parcial incluido IVA: ${{ number_format($totalPriceIva, 2, '.', ',') }}</h4>
                </div>
            </div>
        </div>

        {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
    </x-table>
</div>

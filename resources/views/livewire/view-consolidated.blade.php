<div>
    <x-page-title title="Tabla de consolidado"></x-page-title>
    <x-table>
        <div class="row w-100">
            <div class="col-lg-6 w-25">
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar material" wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i class="ri-close-line"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-centered mb-0">
                <thead>
                    <tr>
                        <th>ORDEN DE COMPRA</th>
                        <th>DESCRIPCIÓN</th>
                        <th>UND.</th>
                        <th>CANTIDAD</th>
                        <th>VALOR UNITARIO SIN IVA</th>
                        <th>VALOR PARCIAL ANTES DE IVA</th>
                        <th>IVA (%)</th>
                        <th>VALOR UNITARIO INCLUIDO IVA</th>
                        <th>VALOR PARCIAL INCLUIDO IVA</th>
                        <th>VALOR RETENCION</th>
                        <th>VALOR TOTAL INCLUIDO IVA Y RETENCION</th>
                        <th>EMPRESA</th>
                        <th>NIT</th>
                        <th>TELEFONO DE CONTACTO</th>
                        <th>DESTINO DE MATERIAL</th>
                        <th>FORMA DE PAGO</th>
                        <th>CUENTA BANCARIA</th>
                        <th>TIPO SOPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder as $order)
                        @foreach ($order->invoiceDetails as $detail)
                            <tr>
                                <td>{{ $detail->id_purchase_order }}</td>
                                <td>{{ $detail->item->name }}</td>
                                <td>{{ $detail->item->unit_measurement }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->price }}</td>
                                <td>{{ $detail->total_price }}</td>
                                <td>{{ $detail->iva }}</td>
                                <td>{{ $detail->price_iva }}</td>
                                <td>{{ $detail->total_price_iva }}</td>
                                <td>{{ $order->retention }}</td>
                                <td>{{ $order->total_payable }}</td>
                                <td>{{ $order->company_name }}</td>
                                <td>{{ $order->company_nit }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->material_destination }}</td>
                                <td>{{ $order->payment_method_id }}</td>
                                <td>{{ $order->bank_name }}</td>
                                <td>{{ $order->account_type }}</td>
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

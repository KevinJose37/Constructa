<div>
    <x-page-title title="Tabla de órdenes de compra"></x-page-title>
    <x-table>

        <div class="row w-100">
            <div class="col-lg-6 w-25">
                <!-- Div a la izquierda -->
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar orden de compra"
                        wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i
                            class="ri-close-line"></i></button>
                </div>
            </div>
        </div>
        <div class="table-responsive">

        
        
        <table class="table table-striped table-centered mb-0">
    <thead>
        <tr class="d-flex">
            <th class="col-md-2">Nombre del proyecto</th>
            <th class="col-md-2">Nombre contratista</th>
            <th class="col-md-2">Nombre compañía destino</th>
            <th class="col-md-2">Total</th>
            <th class="col-md-2">Fecha</th>
            <th class="col-md-2">Pagado</th>
            <th class="col-md-1">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchaseOrder as $order)
        <tr id="purchaseOrderRow_{{ $order->id }}" class="d-flex" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="false" aria-controls="collapse{{ $order->id }}">
            <td class="col-md-2">{{ $order->project->project_name }}</td>
            <td class="col-md-2">{{ $order->contractor_name }}</td>
            <td class="col-md-2">{{ $order->company_name }}</td>
            <td class="col-md-2">{{ $order->total_payable }} COP</td>
            <td class="col-md-2">{{ $order->date }}</td>
            <td class="col-md-2"><livewire:purchase-order-paid-information :order="$order" :wire:key="'purchase-order-paid-' . $order->id"></livewire:purchase-order-paid-information></td>
            <td class="col-md-1">
            <td style="display: flex; align-items: center;" class="col-md-1">
                            <a href="#" class="text-reset fs-19 px-1 delete-project-btn"
                                wire:click.prevent="destroyAlert({{ $order->id }}, '{{ $order->project->project_name }}')">
                                <i class="ri-delete-bin-2-line"></i></a>
                            <a href="{{ route('purchaseorder.get', ['id' => $order->id]) }}"><i
                                    class="ri-search-eye-line"></i></a>            </td>
        </tr>
        <!-- Fila de detalles -->
        <tr id="collapse{{ $order->id }}" class="collapse">
    <td colspan="7">
        @if ($order->invoiceDetails->isNotEmpty())
        
        <table class="table table-bordered">
    <thead>
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
        @foreach ($order->invoiceDetails as $detail) <!-- Iterar solo sobre los detalles de la orden actual -->
                <tr>
                    <td>{{ $detail->id_purchase_order }}</td>
                    <td>{{ $detail->id_item }}</td>
                    <td>{{ $detail->item->name }}</td>
                    <td>{{ $detail->item->unit_measurement }}</td> <!-- Asegúrate de obtener el valor correcto del campo unidad -->
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
    </tbody>
</table>
        @else
            No hay items disponibles.
        @endif
    </td>
</tr>
        @endforeach
    </tbody>
</table>
</div>
        {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
    </x-table>
</div><!-- end col -->

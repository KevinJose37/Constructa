<div>
    <x-page-title title="Órdenes de compra de: {{$project->project_name}}"></x-page-title>
    <x-table>
        @if (!$purchaseOrder->isEmpty())
        <div class="row w-100">
            <div class="col-lg-6 w-25 mb-3">
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
        <table class="table table-striped table-centered mb-3">
    <thead class="table-dark">
        <tr class="d-flex">
            <th class="col-md-1">Numero de orden</th>
            <th class="col-md-2">Nombre de la orden de compra</th>
            <th class="col-md-2">Nombre contratista</th>
            <th class="col-md-2">Nombre compañía destino</th>
            <th class="col-md-2">Total</th>
            <th class="col-md-1">Fecha</th>
            <th class="col-md-1">Pagado</th>
            <th class="col-md-1">Acciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($purchaseOrder as $order)
        <tr id="purchaseOrderRow_{{ $order->id }}" class="d-flex" data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
            aria-controls="collapse{{ $order->id }}">
            <td class="col-md-1">{{ $order->id }}</td>
            <td class="col-md-2">{{ $order->order_name }}</td>
            <td class="col-md-2">{{ $order->contractor_name }}</td>
            <td class="col-md-2">{{ $order->company_name }}</td>
            <td class="col-md-2">${{ number_format($order->total_payable, 2, ',', '.') }} COP</td>
            <td class="col-md-1">{{ $order->date }}</td>
            <td class="col-md-1"><livewire:purchase-order-paid-information :order="$order"
                    :wire:key="'purchase-order-paid-' . $order->id"></livewire:purchase-order-paid-information>
            </td>
            <td class="col-md-1" style="display: flex; align-items: center;">
                <a href="#" class="text-reset fs-19 px-1 delete-project-btn" wire:click.prevent="destroyAlert({{ $order->id }})" style="margin-right: 10px;">
                    <i class="ri-delete-bin-2-line"></i></a>
                <a href="{{ route('purchaseorder.get', ['id' => $order->id]) }}" style="margin-right: 10px;">
                    <i class="ri-search-eye-line"></i></a>
                <a href="{{ route('attachments.page', ['invoiceHeaderId' => $order->id]) }}" style="margin-right: 10px;">
                    <i class="ri-file-upload-fill"></i></a>
                <a href="#" class="text-reset fs-19 px-1"
                    wire:click="$dispatch('setOrderId', { orderId: {{ $order->id }} })"
                    data-bs-toggle="modal" data-bs-target="#editPurchaseOrderInfoModal"
                    style="margin-right: 10px;">
                    <i class="ri-information-fill"></i>
                </a>
            </td>
            <livewire:edit-purchase-order-info :wire:key="'edit-purchase-order-' . $order->id"
                :orderId="$order->id" />
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
                            <th>VALOR RETENCIÓN</th>
                            <th>VALOR TOTAL INCLUIDO IVA Y RETENCIÓN</th>
                            <th>EMPRESA</th>
                            <th>NIT</th>
                            <th>TELÉFONO DE CONTACTO</th>
                            <th>DESTINO DE MATERIAL</th>
                            <th>FORMA DE PAGO</th>
                            <th>CUENTA BANCARIA</th>
                            <th>TIPO SOPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->invoiceDetails as $detail)
                        <tr>
                            <td>{{ $detail->id_purchase_order }}</td>
                            <td>{{ $detail->id_item }}</td>
                            <td>{{ $detail->item->name }}</td>
                            <td>{{ $detail->item->unit_measurement }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>${{ number_format($detail->price, 2, ',', '.') }}</td>
                            <td>${{ number_format($detail->total_price, 2, ',', '.') }}</td>
                            <td>{{ number_format($detail->iva, 2, ',', '.') }}%</td> <!-- Mostrar el porcentaje de IVA -->
                            <td>${{ number_format($detail->price_iva, 2, ',', '.') }}</td>
                            <td>${{ number_format($detail->total_price_iva, 2, ',', '.') }}</td>
                            <td>${{ number_format($order->retention, 2, ',', '.') }}</td>
                            <td>${{ number_format($order->total_payable, 2, ',', '.') }}</td>
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
        @else
        <h3>No se encontró órdenes de compra para este proyecto</h3>
        <a class="btn btn-info" href="{{ url()->previous() }}">Atrás</a>
        @endif
    </tbody>
</table>


    </x-table>
    {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}

</div>
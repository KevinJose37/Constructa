<div>
    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
        <x-page-title title="Tabla de órdenes de compra"></x-page-title>
        @if ($project)
            <span class="badge bg-primary text-white fs-6 shadow-sm px-3 py-2 rounded-pill"
                style="font-weight: 600; letter-spacing: 0.05em;">
                Proyecto: {{ $project->project_name }}
            </span>
        @endif
    </div>
    <x-table>
        <div class="row w-100">
            <div class="col-lg-6 w-25 mb-3">
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar orden de compra"
                        wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i
                            class="ri-close-line"></i></button>
                </div>
            </div>
            {{-- Botón exportar Excel --}}
            <div class="col-auto">
                <button type="button" class="btn btn-success" wire:click="exportExcel">
                    <i class="ri-file-excel-line"></i> Exportar Excel
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-centered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="col-md-1">#</th>
                        <th class="col-md-2">Nombre de la orden</th>
                        <th class="col-md-2">Proyecto</th>
                        <th class="col-md-2">Nombre contratista</th>
                        <th class="col-md-2">Nombre compañía destino</th>
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Fecha</th>
                        <th class="col-md-1">Estado</th>
                        <th class="col-md-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder as $order)
                        @php
                            $estado = $order->purchaseOrderState?->status;
                        @endphp
                        <tr id="purchaseOrderRow_{{ $order->id }}">
                            {{-- Id de la orden --}}
                            <td class="col-md-1" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}"
                                aria-expanded="false" aria-controls="collapse{{ $order->id }}"
                                style="cursor: pointer;">
                                {{ $order->id }}
                            </td>
                            {{-- Nombre de la orden --}}
                            <td class="col-md-2" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}"
                                aria-expanded="false" aria-controls="collapse{{ $order->id }}"
                                style="cursor: pointer;">
                                {{ $order->order_name }}
                            </td>

                            {{-- Nombre del proyecto --}}
                            <td class="col-md-2" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}"
                                aria-expanded="false" aria-controls="collapse{{ $order->id }}"
                                style="cursor: pointer;">
                                {{ $order->project?->project_name }}
                            </td>

                            {{-- Nombre contratista --}}
                            <td class="col-md-2" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $order->id }}" style="cursor: pointer;">
                                {{ $order->contractor_name }}
                            </td>

                            {{-- Nombre compañía --}}
                            <td class="col-md-2" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $order->id }}" style="cursor: pointer;">
                                {{ $order->company_name }}
                            </td>

                            {{-- Total --}}
                            <td class="col-md-1" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $order->id }}" style="cursor: pointer;">
                                {{ $order->total_payable }} COP
                            </td>

                            {{-- Fecha --}}
                            <td class="col-md-1" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $order->id }}" style="cursor: pointer;">
                                {{ $order->date }}
                            </td>

                            {{-- Creador --}}
                            <td class="col-md-1">
                                <livewire:purchase-order-paid-information :order="$order"
                                    :wire:key="'purchase-order-paid-' . md5($order->id . '-' . $order->updated_at)" />
                            </td>
                            <td class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can('delete.purchase')
                                            <li>
                                                <a href="#" class="dropdown-item text-reset"
                                                    wire:click.prevent="destroyAlert({{ $order->id }})">
                                                    <i class="ri-delete-bin-2-line me-2"></i> Eliminar orden de compra
                                                </a>
                                            </li>
                                        @endcan
                                        @can('disable.purchase')
                                            @if ($estado !== \App\Models\PurchaseOrderState::STATUS_PAGADO)
                                                <li>
                                                    <a href="#" class="dropdown-item text-reset"
                                                        wire:click.prevent="activeAlert({{ $order->id }})">
                                                        <i
                                                            class="{{ $order->is_active == true ? 'ri-close-line' : 'ri-check-line' }} me-2"></i>
                                                        {{ $order->is_active == true ? 'Desactivar' : 'Activar' }} orden de
                                                        compra
                                                    </a>
                                                </li>
                                            @endif
                                        @endcan
                                        @can('view.purchase')
                                            <li>
                                                <a href="{{ route('purchaseorder.get', ['id' => $order->id]) }}"
                                                    class="dropdown-item text-reset">
                                                    <i class="ri-search-eye-line me-2"></i> Ver orden de compra
                                                </a>
                                            </li>
                                        @endcan
                                        <li>
                                            @can('update.purchase')
                                                @if ($estado !== \App\Models\PurchaseOrderState::STATUS_PAGADO)
                                                    <a href="{{ route('purchaseorder.edit', ['id' => $order->id]) }}"
                                                        class="dropdown-item text-reset">
                                                        <i class="ri-pencil-fill me-2"></i> Editar orden de compra
                                                    </a>
                                                @endif
                                            @endcan
                                            @can('redirect.materials')
                                                @if ($estado == \App\Models\PurchaseOrderState::STATUS_PAGADO)
                                                    <a href="{{ route('purchaseorder.redirect', ['id' => $order->id]) }}"
                                                        class="dropdown-item text-reset">
                                                        <i class="ri-arrow-right-line me-2"></i> Redireccionar materiales
                                                    </a>
                                                @endif
                                            @endcan
                                        </li>
                                        @can('attachment.purchase')
                                            <li>
                                                <a href="{{ route('attachments.page', ['invoiceHeaderId' => $order->id]) }}"
                                                    class="dropdown-item text-reset">
                                                    <i class="ri-file-upload-fill me-2"></i> Adjuntos
                                                </a>
                                            </li>
                                        @endcan
                                        @can('info.purchase')
                                            <li>
                                                <a href="#" class="dropdown-item text-reset"
                                                    wire:click="$dispatch('setOrderId', { orderId: {{ $order->id }} })"
                                                    data-bs-toggle="modal" data-bs-target="#editPurchaseOrderInfoModal">
                                                    <i class="ri-information-fill me-2"></i> Información
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>

                            {{-- Editar --}}
                            @can('info.purchase')
                                <livewire:edit-purchase-order-info
                                    :wire:key="'edit-purchase-order-' . md5($order->id . '-' . $order->updated_at)"
                                    :orderId="$order->id" />
                            @endcan
                        </tr>

                        <!-- Fila de detalles con mejor estilo Bootstrap -->
                        <tr id="collapse{{ $order->id }}" class="collapse">
                            <td colspan="9">
                                <div class="p-3 bg-light border rounded shadow-sm overflow-auto">
                                    @if ($order->invoiceDetails->isNotEmpty())
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead class="table-light small text-nowrap">
                                                <tr>
                                                    <th class="fw-bold">ORDEN DE COMPRA</th>
                                                    <th class="fw-bold text-center text-uppercase"
                                                        style="min-width: 50px;">ITEM</th>
                                                    <th class="fw-bold">DESCRIPCIÓN</th>
                                                    <th class="fw-bold text-center text-uppercase"
                                                        style="min-width: 50px;">UND.</th>
                                                    <th class="fw-bold text-end text-uppercase">CANTIDAD</th>
                                                    <th class="fw-bold text-end">VALOR UNITARIO SIN IVA</th>
                                                    <th class="fw-bold text-end">VALOR PARCIAL ANTES DE IVA</th>
                                                    <th class="fw-bold text-center text-uppercase">IVA (%)</th>
                                                    <th class="fw-bold text-end">VALOR UNITARIO INCLUIDO IVA</th>
                                                    <th class="fw-bold text-end">VALOR PARCIAL INCLUIDO IVA</th>
                                                    <th class="fw-bold text-end">VALOR RETENCIÓN</th>
                                                    <th class="fw-bold text-end">VALOR TOTAL INCLUIDO IVA Y RETENCIÓN
                                                    </th>
                                                    <th class="fw-bold">EMPRESA</th>
                                                    <th class="fw-bold">NIT</th>
                                                    <th class="fw-bold">TELÉFONO DE CONTACTO</th>
                                                    <th class="fw-bold">DESTINO DE MATERIAL</th>
                                                    <th class="fw-bold">FORMA DE PAGO</th>
                                                    <th class="fw-bold">CUENTA BANCARIA</th>
                                                    <th class="fw-bold">TIPO SOPORTE</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($order->invoiceDetails as $detail)
                                                    <tr>
                                                        <td>{{ $detail->id_purchase_order }}</td>
                                                        <td>{{ $detail->id_item }}</td>
                                                        <td>{{ $detail->item->name }}</td>
                                                        <td>{{ $detail->item->unit_measurement }}</td>
                                                        <td class="text-end">{{ $detail->quantity }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($detail->price, 2, '.', ',') }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($detail->total_price, 2, '.', ',') }}
                                                        </td>
                                                        <td class="text-end">{{ $detail->iva }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($detail->price_iva, 2, '.', ',') }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($detail->total_price_iva, 2, '.', ',') }}
                                                        </td>
                                                        <td class="text-end">
                                                            ${{ number_format($order->retention, 2, '.', ',') }}</td>
                                                        <td class="text-end">
                                                            ${{ number_format($order->total_payable, 2, '.', ',') }}
                                                        </td>
                                                        <td>{{ $order->company_name }}</td>
                                                        <td>{{ $order->company_nit }}</td>
                                                        <td>{{ $order->phone }}</td>
                                                        <td>{{ $order->material_destination }}</td>
                                                        <td>{{ $order->paymentMethod->payment_name }}</td>
                                                        <td>{{ $order->bank_name }}</td>
                                                        <td>{{ $order->supportType->support_name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        No hay items disponibles.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $purchaseOrder->links(data: ['scrollTo' => false]) }}
        </div>
    </x-table>
</div><!-- end col -->

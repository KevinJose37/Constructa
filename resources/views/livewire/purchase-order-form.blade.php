<div class="container my-4">
    @if (isset($project))
        <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
            <span class="badge bg-primary text-white fs-6 shadow-sm px-3 py-2 rounded-pill"
                style="font-weight: 600; letter-spacing: 0.05em;">
                Proyecto: {{ $project->project_name }}
            </span>

        </div>
    @endif
    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4">
                @if ($isViewMode)
                    Orden de compra n°{{ $order->invoice_number }}
                @else
                    Orden de compra #{{ $lastInvoiceId + 1 }}
                @endif
            </h3>

            <!-- Order Name -->
            <div class="mb-4">
                <label for="inputApodo" class="form-label">Nombre de la orden</label>
                <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10" id="inputApodo"
                    placeholder="Identifique esta orden de compra" wire:model.lazy="order_name"
                    @disabled($isViewMode)>
                @error('order_name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="row g-4">
                <!-- Izquierda -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-3">
                        <h5 class="card-title mb-3">Información del Contratista</h5>

                        <div class="mb-3">
                            <label for="inputContratista" class="form-label">Contratista</label>
                            <input type="text" class="form-control" id="inputContratista"
                                wire:model.lazy="contractor_name" @disabled(true)>
                            @error('contractor_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputNitOne" class="form-label">NIT Contratista</label>
                            <input type="text" class="form-control" id="inputNitOne" wire:model.lazy="contractor_nit"
                                @disabled(true)>
                            @error('contractor_nit')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputResponsable" class="form-label">Responsable</label>
                            <input type="text" class="form-control" id="inputResponsable"
                                wire:model.lazy="responsible_name" @disabled(true)>
                            @error('responsible_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputFecha" class="form-label">Fecha</label>
                            <input type="text" class="form-control" id="inputFecha" wire:model.lazy="currentDate"
                                @disabled(true)>
                            @error('currentDate')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputRetencion" class="form-label">Retención (%)</label>
                            <input type="number" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                                id="inputRetencion" wire:model.lazy="retencionPercentage" @disabled($isViewMode)
                                @if (!$isViewMode) wire:keydown="updateTotals" @endif>
                            @error('retencionPercentage')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Derecha -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-3">
                        <h5 class="card-title mb-3">Información de la Empresa</h5>

                        <div class="mb-3">
                            <label for="inputEmpresa" class="form-label">Empresa</label>
                            <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                                id="inputEmpresa" wire:model.lazy="company_name" @disabled($isViewMode)>
                            @error('company_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputSNit" class="form-label">NIT Empresa</label>
                            <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                                id="inputSNit" wire:model.lazy="company_nit" @disabled($isViewMode)>
                            @error('company_nit')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputTele" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                                id="inputTele" wire:model.lazy="phone" @disabled($isViewMode)>
                            @error('phone')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputDestin" class="form-label">Destino Material</label>
                            <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                                id="inputDestin" wire:model.lazy="material_destination" @disabled($isViewMode)>
                            @error('material_destination')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forma de pago y cuenta bancaria -->
            <div class="card mt-4 border-0 shadow-sm p-4">
                <h5 class="card-title mb-4">Forma de Pago</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="inputType" class="form-label">Método de Pago</label>
                        @if ($isViewMode)
                            <input type="text" class="form-control" id="inputType"
                                value="{{ $paymentMethodName }}" disabled>
                        @else
                            <select class="form-select" id="inputType" wire:model.lazy="payment_method_id">
                                <option value="">Seleccione</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->payment_name }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('payment_method_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="inputBank" class="form-label">Banco</label>
                        <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                            id="inputBank" wire:model.lazy="bank_name" @disabled($isViewMode)>
                        @error('bank_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="inputAccountType" class="form-label">Tipo de Cuenta</label>
                        <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                            id="inputAccountType" wire:model.lazy="account_type" @disabled($isViewMode)>
                        @error('account_type')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="inputAccountNumber" class="form-label">N° Cuenta</label>
                        <input type="text" class="form-control bg-primary p-2 text-dark bg-opacity-10"
                            id="inputAccountNumber" wire:model.lazy="account_number" @disabled($isViewMode)>
                        @error('account_number')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="inputSupport" class="form-label">Tipo de Soporte</label>
                        @if ($isViewMode)
                            <input type="text" class="form-control" id="inputSupport"
                                value="{{ $paymentSupport }}" disabled>
                        @else
                            <select class="form-select" id="inputSupport" wire:model.lazy="support_type_id">
                                <option value="">Seleccione</option>
                                @foreach ($paymentSupport as $support)
                                    <option value="{{ $support->id }}">{{ $support->support_name }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('support_type_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="">
                @livewire('attachments-page', ['invoiceHeaderId' => $order->id ?? null, 'onlyView' => $isViewMode])
            </div>

            @if (!$isViewMode)
                <div>
                    <livewire:create-purchase-order-modal></livewire:create-purchase-order-modal>
                    <livewire:create-material-modal></livewire:create-material-modal>
                </div>
            @endif

            <!-- Tabla de productos -->

            @if (empty($selectedItems) && !$isViewMode)
                <div class="card mt-4 border-0 shadow-sm p-4">
                    <h5 class="card-title mb-4">Productos</h5>
                    <p class="text-muted">No hay productos seleccionados</p>
                </div>
            @elseif ($isViewMode || !empty($selectedItems))
                <div class="card mt-4 border-0 shadow-sm p-4">
                    <h5 class="card-title mb-4">Productos</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Cantidad</th>
                                    <th>Und</th>
                                    <th>Valor UN sin IVA</th>
                                    <th>Valor PAR antes IVA</th>
                                    <th>IVA</th>
                                    @if (!$isViewMode)
                                        <th>IVA producto</th>
                                    @endif
                                    <th>Valor UN IVA</th>
                                    <th>Valor PAR IVA</th>
                                    @if (!$isViewMode)
                                        <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($isViewMode)
                                    @foreach ($order->invoiceDetails as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <b>{{ $detail->item->name }}</b> <br />
                                            </td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ $detail->item->unit_measurement }}</td>
                                            <td>${{ number_format($detail->price, 2, ',', '.') }}</td>
                                            <td>${{ number_format($detail->total_price, 2, ',', '.') }}</td>
                                            <td>${{ number_format($detail->iva, 2, ',', '.') }}</td>
                                            <td>${{ number_format($detail->price_iva, 2, ',', '.') }}</td>
                                            <td>${{ number_format($detail->total_price_iva, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($selectedItems as $index => $currentItem)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <b>{{ $currentItem['name'] }}</b>
                                            </td>
                                            <td>{{ $currentItem['quantity'] }}</td>
                                            <td>{{ $currentItem['unit_measurement'] ?? '' }}</td>
                                            <td>${{ $currentItem['price'] }}</td>
                                            <td>${{ $currentItem['totalPrice'] }}</td>
                                            <td>${{ $currentItem['iva'] }}</td>
                                            <td>{{ $currentItem['ivaProduct'] }}%</td>
                                            <td>${{ $currentItem['priceIva'] }}</td>
                                            <td>${{ $currentItem['totalPriceIva'] }}</td>
                                            <td>
                                                <a href="#" class="text-reset fs-19 px-1 delete-project-btn"
                                                    wire:click.prevent="destroyAlertPurchase({{ $currentItem['id'] }}, '{{ $currentItem['name'] }}', '{{ $index }}')">
                                                    <i class="ri-delete-bin-2-line"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- end row -->
            <div class="row mt-4">
    <!-- Observaciones -->
    <div class="col-12 col-md-4">
        <div class="form-floating">
            <textarea class="form-control" placeholder="Deje un comentario aquí" id="floatingTextarea"
                wire:model.lazy="general_observations" @disabled($isViewMode)></textarea>
            <label for="floatingTextarea">Observaciones Generales</label>
            @error('general_observations')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div> <!-- end col-->

                <!-- Valor antes de IVA -->
    <div class="col-12 col-md-4 mt-3 mt-md-0">
        <h6 class="text-muted">Valor antes de IVA:</h6>
        @if ($isViewMode)
            <p class="fs-13"><strong>Subtotal: </strong> <span>${{ number_format($order->subtotal_before_iva, 2, ',', '.') }}</span></p>
            <p class="fs-13"><strong>IVA: </strong> <span>${{ number_format($order->total_iva, 2, ',', '.') }}</span></p>
            <p class="fs-13"><strong>Valor total: </strong> <span>${{ number_format($order->total_with_iva, 2, ',', '.') }}</span></p>
            <p class="fs-13"><strong>Retención: </strong> <span>${{ number_format($order->retention, 2, ',', '.') }}</span></p>
            <p class="fs-13"><strong>Valor por pagar: </strong> <span>${{ number_format($order->total_payable, 2, ',', '.') }}</span></p>
        @else
            <p class="fs-13"><strong>Subtotal: </strong> <span>${{ $totalPurchase }}</span></p>
            <p class="fs-13"><strong>IVA: </strong> <span>${{ $totalIVA }}</span></p>
            <p class="fs-13"><strong>Valor total: </strong> <span>${{ $totalPurchaseIva }}</span></p>
            <p class="fs-13"><strong>Retención: </strong> <span>${{ $retencion }}</span></p>
            <p class="fs-13"><strong>Valor por pagar: </strong> <span>${{ $totalPay }}</span></p>
        @endif
    </div> <!-- end col-->

    <!-- Valor después de IVA -->
    <div class="col-12 col-md-4 mt-3 mt-md-0">
        <div class="text-md-end">
            <h6 class="text-muted">Valor después de IVA:</h6>
            @if ($isViewMode)
                <p><b>Sub-total:</b> <span>${{ number_format($order->total_with_iva, 2, ',', '.') }}</span></p>
                <h3>${{ number_format($order->total_payable, 2, ',', '.') }} COP</h3>
            @else
                <p><b>Sub-total:</b> <span>${{ $totalPurchaseIva }}</span></p>
                <h3>${{ $totalPay }} COP</h3>
            @endif
        </div>
    </div> <!-- end col-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i>
                            Imprimir</a>
                        @if ($isViewMode)
                            <a class="btn btn-info" href="{{ url()->previous() }}">Atrás</a>
                        @else
                            <a href="javascript:void(0);" class="btn btn-info" wire:click="storeHeader">Guardar</a>
                        @endif
                    </div>
                </div>
                <!-- end buttons -->
            </div>
        </div>
    </div>
</div>

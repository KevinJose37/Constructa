<div class="container my-4">
    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4">
                Orden de compra n°{{ $order->id }}

            </h3>

            <!-- Order Name -->
            <div class="mb-4">
                <label for="inputApodo" class="form-label">Nombre de la orden</label>
                <input type="text" class="form-control" id="inputApodo" placeholder="Identifique esta orden de compra"
                    wire:model="order_name">
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
                                wire:model="contractor_name" @disabled(true)>
                            @error('contractor_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputNitOne" class="form-label">NIT Contratista</label>
                            <input type="text" class="form-control" id="inputNitOne" wire:model="contractor_nit"
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
                                wire:model="responsible_name" @disabled(true)>
                            @error('responsible_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputFecha" class="form-label">Fecha</label>
                            <input type="text" class="form-control" id="inputFecha" wire:model="currentDate"
                                @disabled(true)>
                            @error('currentDate')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputRetencion" class="form-label">Retención (%)</label>
                            <input type="number" class="form-control" id="inputRetencion"
                                wire:model="retencionPercentage" wire:keydown="updateTotals">
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
                            <input type="text" class="form-control" id="inputEmpresa" wire:model="company_name">
                            @error('company_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputSNit" class="form-label">NIT Empresa</label>
                            <input type="text" class="form-control" id="inputSNit" wire:model="company_nit">
                            @error('company_nit')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputTele" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="inputTele" wire:model="phone">
                            @error('phone')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputDestin" class="form-label">Destino Material</label>
                            <input type="text" class="form-control" id="inputDestin"
                                wire:model="material_destination">
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

                        <select class="form-select" id="inputType" wire:model="payment_method_id">
                            <option value="">Seleccione</option>
                            @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->payment_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="inputBank" class="form-label">Banco</label>
                        <input type="text" class="form-control" id="inputBank" wire:model="bank_name">
                        @error('bank_name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="inputAccountType" class="form-label">Tipo de Cuenta</label>
                        <input type="text" class="form-control" id="inputAccountType" wire:model="account_type">
                        @error('account_type')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="inputAccountNumber" class="form-label">N° Cuenta</label>
                        <input type="text" class="form-control" id="inputAccountNumber"
                            wire:model="account_number">
                        @error('account_number')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="inputSupport" class="form-label">Tipo de Soporte</label>

                        <select class="form-select" id="inputSupport" wire:model="support_type_id">
                            <option value="">Seleccione</option>
                            @foreach ($paymentSupport as $support)
                            <option value="{{ $support->id }}">{{ $support->support_name }}</option>
                            @endforeach
                        </select>
                        @error('support_type_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="">
                @livewire('attachments-page', ['invoiceHeaderId' => $order->id ?? null, 'onlyView' => false])
            </div>

            <div>
                <livewire:create-purchase-order-modal></livewire:create-purchase-order-modal>
            </div>

            <!-- Tabla de productos -->
            @if (!empty($selectedItems))
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
                                <th>IVA producto</th>
                                <th>Valor UN IVA</th>
                                <th>Valor PAR IVA</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

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
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- end row -->
            <div class="row mt-4">
                <div class="col-4">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Deje un comentario aquí" id="floatingTextarea"
                            wire:model="general_observations"></textarea>
                        <label for="floatingTextarea">Observaciones Generales</label>
                        @error('general_observations')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div> <!-- end col-->

                <div class="col-4">
                    <h6 class="text-muted">Valor antes de IVA:</h6>

                    <p class="fs-13"><strong>Subtotal: </strong> <span>
                            ${{ $totalPurchase }}</span></p>
                    <p class="fs-13"><strong>IVA: </strong> <span>
                            ${{ $totalIVA }}</span></p>
                    <p class="fs-13"><strong>Valor total: </strong> <span>
                            ${{ $totalPurchaseIva }}</span></p>
                    <p class="fs-13"><strong>Retención: </strong> <span>
                            ${{ $retencion }}</span></p>
                    <p class="fs-13"><strong>Valor por pagar: </strong> <span>
                            ${{ $totalPay }}</span></p>
                </div> <!-- end col-->

                <div class="col-4">
                    <div class="text-sm-end">
                        <h6 class="text-muted">Valor después de IVA:</h6>
                        <p><b>Sub-total:</b> <span class="float-end">
                                ${{ $totalPurchaseIva }}</span>
                        </p>
                        <h3>${{ $totalPay }} COP</h3>
                    </div>
                </div> <!-- end col-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i>
                            Imprimir</a>
                        <a href="javascript:void(0);" class="btn btn-info" wire:click="updateHeader">Guardar</a>
                    </div>
                </div>
                <!-- end buttons -->
            </div>
        </div>
    </div>
</div>
<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start mb-3">
                        <h4 class="m-0 d-print-none">Orden de compra n°{{ $lastInvoiceId + 1 }}</h4>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="inputApodo" placeholder="Ingrese el nombre para identificar la orden de compra" wire:model="order_name">
                        @error('order_name')
                        <div class="invalid-feedback {{ $errors->has('order_name') ? 'd-block' : '' }}">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="row mb-3 align-items-center">
                            <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Fecha</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm" id="inputFecha" placeholder="Fecha" wire:model="currentDate">
                            </div>
                            @error('currentDate')
                            <div class="invalid-feedback {{ $errors->has('currentDate') ? 'd-block' : '' }}">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
                        <div class="row mb-3">
                            <label for="inputContratista" class="col-sm-2 col-form-label col-form-label-sm">Contratista</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputContratista" placeholder="Ingrese el nombre del contratista" wire:model="contractor_name">
                                @error('contractor_name')
                                <div class="invalid-feedback {{ $errors->has('contractor_name') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputNitOne" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputNitOne" placeholder="Ingrese el NIT" wire:model="contractor_nit">
                                @error('contractor_nit')
                                <div class="invalid-feedback {{ $errors->has('contractor_nit') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
    <label for="inputResponsable" class="col-sm-2 col-form-label col-form-label-sm">Responsable</label>
    <div class="col-sm-6">
        <input type="text" class="form-control form-control-sm" id="inputResponsable"
               placeholder="Ingrese el nombre del responsable" wire:model="responsible_name" readonly>
        @error('responsible_name')
            <div class="invalid-feedback {{ $errors->has('responsible_name') ? 'd-block' : '' }}">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

                    </div>
                    <!-- end col-->

                    <div class="col">
                        <div class="row mb-3">
                            <label for="inputEmpresa" class="col-sm-2 col-form-label col-form-label-sm">Empresa</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputEmpresa" placeholder="Ingrese el nombre de la empresa" wire:model="company_name">
                                @error('company_name')
                                <div class="invalid-feedback {{ $errors->has('company_name') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputSNit" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputSNit" placeholder="Ingrese el NIT" wire:model="company_nit">
                                @error('company_nit')
                                <div class="invalid-feedback {{ $errors->has('company_nit') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputTele" class="col-sm-2 col-form-label col-form-label-sm">Teléfono</label>
                            <div class="col-sm-6">
                                <input type="tel" class="form-control form-control-sm" id="inputTele" placeholder="Ingrese el teléfono" wire:model="phone">
                                @error('phone')
                                <div class="invalid-feedback {{ $errors->has('phone') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDestin" class="col-sm-2 col-form-label col-form-label-sm">Destino
                                material</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputDestin" placeholder="Ingrese el destino del material" wire:model="material_destination">
                                @error('material_destination')
                                <div class="invalid-feedback {{ $errors->has('material_destination') ? 'd-block' : '' }}">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col">
                        <div class="text-sm-end">
                            <div class="row mb-3">
                                <label for="inputType" class="col-sm-2 col-form-label col-form-label-sm">Forma de
                                    pago</label>
                                <div class="col-sm-6">
                                    <select class="form-control form-control-sm" id="inputType" wire:model="payment_method_id">
                                        <option selected>Forma de pago</option>
                                        @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->payment_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_method_id')
                                    <div class="invalid-feedback {{ $errors->has('payment_method_id') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label col-form-label-sm">Cuenta bancaria</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputBank" placeholder="Banco" wire:model="bank_name">
                                    @error('bank_name')
                                    <div class="invalid-feedback {{ $errors->has('bank_name') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputAccountType" placeholder="Tipo" wire:model="account_type">
                                    @error('account_type')
                                    <div class="invalid-feedback {{ $errors->has('account_type') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control form-control-sm" id="inputAccountNumber" placeholder="Número de Cuenta" wire:model="account_number">
                                    @error('account_number')
                                    <div class="invalid-feedback {{ $errors->has('account_number') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <label for="inputType" class="col-sm-2 col-form-label col-form-label-sm">Tipo
                                    soporte</label>
                                <div class="col-sm-6">
                                    <select class="form-control form-control-sm" id="inputType" wire:model="support_type_id">
                                        <option selected>Tipo de soporte</option>
                                        @foreach ($paymentSupport as $method)
                                        <option value="{{ $method->id }}">{{ $method->support_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('support_type_id')
                                    <div class="invalid-feedback {{ $errors->has('support_type_id') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div> <!-- end col-->
                    </div>
                </div>

                <div>
                    <livewire:create-purchase-order-modal></livewire:create-purchase-order-modal>
                </div>

                <!-- end row -->

                @if (!empty($selectedItems))
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-hover table-borderless mb-0 mt-3">
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Cantidad</th>
                                        <th>Und</th>
                                        <th>Valor UN sin IVA</th>
                                        <th>Valor PAR antes IVA</th>
                                        <th>IVA</th>
                                        <th>Valor UN IVA</th>
                                        <th>Valor PAR IVA</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedItems as $index => $currentItem)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <b>{{ $currentItem['name'] }}</b> <br />
                                            {{-- {{ $currentItem['description'] }} --}}
                                        </td>
                                        <td>{{ $currentItem['quantity'] }}</td>
                                        <td>${{ $currentItem['price'] }}</td>
                                        <td>${{ $currentItem['price'] }}</td>
                                        <td>${{ $currentItem['totalPrice'] }}</td>
                                        <td>${{ $currentItem['iva'] }}</td>
                                        <td>${{ $currentItem['priceIva'] }}</td>
                                        <td>${{ $currentItem['totalPriceIva'] }}</td>
                                        <td> <a href="#" class="text-reset fs-19 px-1 delete-project-btn" wire:click.prevent="destroyAlertPurchase({{ $currentItem['id'] }}, '{{ $currentItem['name'] }}', '{{ $index }}')">
                                                <i class="ri-delete-bin-2-line"></i></a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    </div> <!-- end col -->
                </div>

                @endif

                <!-- end row -->
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deje un comentario aquí" id="floatingTextarea" wire:model="general_observations"></textarea>
                            <label for="floatingTextarea">Observaciones Generales</label>
                        </div>
                    </div> <!-- end col-->

                    <div class="col-4">
                        <h6 class="text-muted">Valor antes de IVA:</h6>
                        <p class="fs-13"><strong>Subtotal: </strong> <span>
                                {{ $totalPurchase }}</span></p>
                        <p class="fs-13"><strong>IVA: </strong> <span> {{ $totalIVA }}</span>
                        </p>
                        <p class="fs-13"><strong>Valor total: </strong> <span>
                                {{ $totalPurchaseIva }}</span></p>
                        <p class="fs-13"><strong>Retención: </strong> <span>
                                {{ $retencion }}</span></p>
                        <p class="fs-13"><strong>Valor por pagar: </strong> <span>
                                {{ $totalPay }}</span></p>
                    </div> <!-- end col-->

                    <div class="col-4">
                        <div class="text-sm-end">
                            <h6 class="text-muted">Valor después de IVA:</h6>
                            <p><b>Sub-total:</b> <span class="float-end">{{ $totalPurchaseIva }}</span></p>
                            <h3>{{ $totalPay }} COP</h3>
                        </div>
                    </div> <!-- end col-->
                </div>
                <!-- end row-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i>
                            Imprimir</a>
                        <a href="javascript:void(0);" class="btn btn-info" wire:click="storeHeader">Guardar</a>
                    </div>
                </div>
                <!-- end buttons -->


            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->
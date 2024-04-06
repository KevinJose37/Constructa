<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start mb-3">
                        <h4 class="m-0 d-print-none">Orden de compra n°{{ $lastInvoiceId + 1 }}</h4>
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="row mb-3 align-items-center">
                            <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Fecha</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm" id="inputFecha" placeholder="Fecha" wire:model="currentDate">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputContratista" class="col-sm-2 col-form-label col-form-label-sm">Contratista</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputContratista" placeholder="Ingrese el nombre del contratista" wire:model="contractor_name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputNitOne" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputNitOne" placeholder="Ingrese el NIT" wire:model="contractor_nit">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputResponsable" class="col-sm-2 col-form-label col-form-label-sm">Responsable</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputResponsable" placeholder="Ingrese el nombre del responsable" wire:model="responsible_name">
                            </div>
                        </div>
                    </div>
                    <!-- end col-->

                    <div class="col">
                        <div class="row mb-3">
                            <label for="inputEmpresa" class="col-sm-2 col-form-label col-form-label-sm">Empresa</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputEmpresa" placeholder="Ingrese el nombre de la empresa" wire:model="company_name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputSNit" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputSNit" placeholder="Ingrese el NIT" wire:model="company_nit">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputTele" class="col-sm-2 col-form-label col-form-label-sm">Teléfono</label>
                            <div class="col-sm-6">
                                <input type="tel" class="form-control form-control-sm" id="inputTele" placeholder="Ingrese el teléfono" wire:model="phone">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDestin" class="col-sm-2 col-form-label col-form-label-sm">Destino material</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputDestin" placeholder="Ingrese el destino del material" wire:model="material_destination">
                            </div>
                        </div>
                    </div> <!-- end col-->

                    <div class="col">
                        <div class="text-sm-end">
                            <div class="row mb-3">
                                <label for="inputType" class="col-sm-2 col-form-label col-form-label-sm">Forma de pago</label>
                                <div class="col-sm-6">
                                    <select class="form-control form-control-sm" id="inputType" wire:model="payment_method_id">
                                        <option selected>Forma de pago</option>
                                        @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->payment_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label col-form-label-sm">Cuenta bancaria</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputBank" placeholder="Banco" wire:model="bank_name">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputAccountType" placeholder="Tipo" wire:model="account_type">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control form-control-sm" id="inputAccountNumber" placeholder="Número de Cuenta" wire:model="account_number">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputSupport" class="col-sm-2 col-form-label col-form-label-sm">Tipo soporte</label>
                                <div class="col-sm-6">
                                    <select class="form-control form-control-sm" id="inputType" wire:model="support_type_id">
                                        <option selected >Tipo de soporte</option>
                                        @foreach ($paymentSupport as $method)
                                        <option value="{{ $method->id }}">{{ $method->support_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>

                <!-- end row -->
                <div class="row">
                    <div class="col" wire:ignore>
                        <select name="idUser" id="item-select" class="form-select select2">
                            <option disabled selected>Seleccione una opción...</option>
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col">
                        <input type="number" class="form-control form-control-sm" id="quantityItem" wire:model.live="formPurchase.quantityItem" placeholder="Ingrese la cantidad">
                        @error('formPurchase.quantityItem')
                        <div class="invalid-feedback {{ $errors->has('formPurchase.quantityItem') ? 'd-block' : '' }}">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col col-md-auto">
                        <button class="btn btn-success" id="assign-item" wire:click='store'><i class="ri-send-plane-fill"></i></button>
                    </div>

                </div>
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
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedItems as $index => $currentItem)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <b>{{ $currentItem['name'] }}</b> <br />
                                            {{ $currentItem['description'] }}
                                        </td>
                                        <td>{{ $currentItem['quantity'] }}</td>
                                        <td>${{ $currentItem['price'] }}</td>
                                        <td>${{ $currentItem['price'] }}</td>
                                        <td>${{ $currentItem['totalPrice'] }}</td>
                                        <td>${{ $currentItem['iva'] }}</td>
                                        <td>${{ $currentItem['priceIva'] }}</td>
                                        <td>${{ $currentItem['totalPriceIva'] }}</td>
                                        <td class="text-end">$1799.00</td>
                                        <td> <a href="#" class="text-reset fs-19 px-1 delete-project-btn" wire:click.prevent="destroyAlertPurchase({{ $currentItem['id'] }}, '{{ $currentItem['name'] }}')">
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
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            <label for="floatingTextarea">Observaciones Generales</label>
                        </div>
                    </div> <!-- end col-->

                    <div class="col-4">
                        <h6 class="text-muted">Valor antes de IVA:</h6>

                        <p class="fs-13"><strong>Subtotal: </strong> <span> &nbsp;&nbsp;&nbsp; Jan 17, 2023</p>
                        <p class="fs-13"><strong>IVA: </strong> <span>&nbsp;&nbsp;&nbsp; <span>Ejemplo</span></p>
                        <p class="fs-13"><strong>Valor total: </strong> <span>&nbsp;&nbsp;&nbsp; 123123123</p>
                        <p class="fs-13"><strong>Retención: </strong> <span>&nbsp;&nbsp;&nbsp; <span>Ejemplo</span>
                        </p>
                        <p class="fs-13"><strong>Valor por pagar </strong> <span>&nbsp;&nbsp;&nbsp;
                                <span>Ejemplo</span></p>

                    </div> <!-- end col-->

                    <div class="col-4">
                        <div class="text-sm-end">
                            <h6 class="text-muted">Valor después de IVA:</h6>

                            <p><b>Sub-total:</b> <span class="float-end">$4120.00</span></p>
                            <p><b>VAT (12.5):</b> <span class="float-end">$515.00</span></p>
                            <h3>{{$totalPurchaseIva}} USD</h3>

                        </div>
                    </div> <!-- end col-->
                </div>
                <!-- end row-->
                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i>
                            Print</a>
                        <a href="javascript:void(0);" class="btn btn-info" wire:click="storeHeader">Guardar</a>
                    </div>
                </div>
                <!-- end buttons -->

            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->

@push('scripts')
<script type="module">
    $(document).ready(function() {
        $('#item-select').select2();
        $('#item-select').on('change', function(e) {
            @this.set('currentSelect', e.target.value);
        });

    });
</script>
@endpush
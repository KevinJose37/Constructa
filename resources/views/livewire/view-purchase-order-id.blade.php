<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-start mb-3">
                        <h4 class="m-0 d-print-none">Orden de compra n°{{ $currentOrder->id }}</h4>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="inputApodo" placeholder="inputApodo"
                            value="{{ $currentOrder->order_name }}" @disabled(true)>

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="row mb-3 align-items-center">
                            <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Fecha</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm" id="inputFecha"
                                    placeholder="Fecha" value="{{ $currentOrder->date }}" @disabled(true)>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputContratista"
                                class="col-sm-2 col-form-label col-form-label-sm">Contratista</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputContratista"
                                    placeholder="Ingrese el nombre del contratista"
                                    value="{{ $currentOrder->contractor_name }}" @disabled(true)>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputNitOne" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputNitOne"
                                    placeholder="Ingrese el NIT" value="{{ $currentOrder->contractor_nit }}"
                                    @disabled(true)>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputResponsable"
                                class="col-sm-2 col-form-label col-form-label-sm">Responsable</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputResponsable"
                                    placeholder="Ingrese el nombre del responsable"
                                    value="{{ $currentOrder->responsible_name }}" @disabled(true)>
                            </div>

                        </div>
                    </div>
                    <!-- end col-->

                    <div class="col">
                        <div class="row mb-3">
                            <label for="inputEmpresa" class="col-sm-2 col-form-label col-form-label-sm">Empresa</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputEmpresa"
                                    placeholder="Ingrese el nombre de la empresa"
                                    value="{{ $currentOrder->company_name }}" @disabled(true)>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputSNit" class="col-sm-2 col-form-label col-form-label-sm">NIT</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputSNit"
                                    placeholder="Ingrese el NIT" value="{{ $currentOrder->company_nit }}"
                                    @disabled(true)>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputTele" class="col-sm-2 col-form-label col-form-label-sm">Teléfono</label>
                            <div class="col-sm-6">
                                <input type="tel" class="form-control form-control-sm" id="inputTele"
                                    placeholder="Ingrese el teléfono" value="{{ $currentOrder->phone }}"
                                    @disabled(true)>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDestin" class="col-sm-2 col-form-label col-form-label-sm">Destino
                                material</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="inputDestin"
                                    placeholder="Ingrese el destino del material"
                                    value="{{ $currentOrder->material_destination }}" @disabled(true)>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col">
                        <div class="text-sm-end">
                            <div class="row mb-3">
                                <label for="inputType" class="col-sm-2 col-form-label col-form-label-sm">Forma de
                                    pago</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" id="inputType"
                                        value="{{ $paymentMethodName }}" @disabled(true)>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label col-form-label-sm">Cuenta bancaria</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputBank"
                                        placeholder="Banco" value="{{ $currentOrder->bank_name }}"
                                        @disabled(true)>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control form-control-sm" id="inputAccountType"
                                        placeholder="Tipo" value="{{ $currentOrder->account_type }}"
                                        @disabled(true)>

                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control form-control-sm"
                                        id="inputAccountNumber" placeholder="Número de Cuenta"
                                        value="{{ $currentOrder->account_number }}" @disabled(true)>
                                </div>
                            </div>

                            <div class="row">
                                <label for="inputType" class="col-sm-2 col-form-label col-form-label-sm">Tipo
                                    soporte</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" id="inputType"
                                        value="{{ $paymentSupport }}" @disabled(true)>
                                </div>
                            </div>

                        </div> <!-- end col-->
                    </div>
                </div>
                <!-- end row -->
                {{-- @dump($currentOrder->invoiceDetails->items) --}}

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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currentOrder->invoiceDetails as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <b>{{ $detail->item->name }}</b> <br />
                                            </td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ $detail->price }}</td>
                                            <td>{{ $detail->price }}</td>
                                            <td>{{ $detail->total_price }}</td>
                                            <td>{{ $detail->iva }}</td>
                                            <td>{{ $detail->price_iva }}</td>
                                            <td>{{ $detail->total_price_iva }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    </div> <!-- end col -->
                </div>


                <!-- end row -->
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deje un comentario aquí" id="floatingTextarea"
                                value="{{ $currentOrder->general_observations }}" @disabled(true)></textarea>
                            <label for="floatingTextarea">Observaciones Generales</label>
                        </div>
                    </div> <!-- end col-->

                    <div class="col-4">
                        <h6 class="text-muted">Valor antes de IVA:</h6>
                        <p class="fs-13"><strong>Subtotal: </strong> <span>
                                {{ $currentOrder->subtotal_before_iva }}</span></p>
                        <p class="fs-13"><strong>IVA: </strong> <span> {{ $currentOrder->total_iva }}</span>
                        </p>
                        <p class="fs-13"><strong>Valor total: </strong> <span>
                                {{ $currentOrder->total_with_iva }}</span></p>
                        <p class="fs-13"><strong>Retención: </strong> <span>
                                {{ $currentOrder->retention }}</span></p>
                        <p class="fs-13"><strong>Valor por pagar: </strong> <span>
                                {{ $currentOrder->total_payable }}</span></p>
                    </div> <!-- end col-->

                    <div class="col-4">
                        <div class="text-sm-end">
                            <h6 class="text-muted">Valor después de IVA:</h6>
                            <p><b>Sub-total:</b> <span class="float-end">{{ $currentOrder->total_with_iva }}</span>
                            </p>
                            <h3>{{ $currentOrder->total_payable }} COP</h3>
                        </div>
                    </div> <!-- end col-->
                </div>
                <!-- end row-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="ri-printer-line"></i>
                            Imprimir</a>
                        <a class="btn btn-info" href="{{ url()->previous() }}">Atrás</a>
                    </div>
                </div>
                <!-- end buttons -->
            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->

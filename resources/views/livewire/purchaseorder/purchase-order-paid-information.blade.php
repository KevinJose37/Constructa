<div>
    @php

        $classButton = 'danger';
        $statusButton = 'Sin procesar';
        $clickAction = "\$set('form.open', true)";
        $returnFlag = false;
        $showDecision = true;

        if (!(Auth::user()->can('approved_tech.purchase') && Auth::user()->can('approved_account.purchase'))) {
            // Verificar si el usuario NO puede realizar ninguna de las acciones individualmente
            if (!Auth::user()->can('approved_tech.purchase') && !Auth::user()->can('approved_account.purchase')) {
                $clickAction = '';
                $returnFlag = true;
            }
        }

        if (!is_null($order->paidInformation) && $returnFlag !== true) {
            // Si ya se llenó algún dato, por el contrario aún no se ha gestionado la orden unu

            // Validamos el status de la orden de compra
            // Al menos una es true, pero no ambas
            if ($order->paidInformation->approved_tech xor $order->paidInformation->approved_account) {
                $classButton = 'warning';
                $statusButton = 'Pendiente';
                // Verificar si es técnico y su decisión ya fue tomada
                if (!Auth::user()->hasRole('Residente') && !Auth::user()->hasRole('Contador')) {
                    if ($order->paidInformation->approved_tech) {
                        $clickAction = ''; // Decisión ya tomada
                    } else {
                        $clickAction = "\$set('form.open', true)";
                    }
                }

                // Verificar si es Contador y su decisión ya fue tomada
                if (Auth::user()->hasRole('Contador')) {
                    if ($order->paidInformation->approved_account) {
                        $clickAction = ''; // Decisión ya tomada
                    } else {
                        $clickAction = "\$set('form.open', true)";
                    }
                }
            }

            if ($order->paidInformation->approved_tech && $order->paidInformation->approved_account) {
                $showDecision = false;
                if (
                    !empty($order->paidInformation->payment_person) ||
                    !empty($order->paidInformation->payment_method) ||
                    !empty($order->paidInformation->payment_how) ||
                    !empty($order->paidInformation->payment_date)
                ) {
                    $classButton = 'success';
                    $statusButton = 'Pagado';
                    $clickAction = "\$set('form.open', true)";
                } else {
                    $classButton = 'warning';
                    $statusButton = 'Por confirmar';
                    $clickAction = Auth::user()->can('paid.purchase') ? "\$set('form.open', true)" : '';
                }
            }
        }

    @endphp
    <!-- Div a la derecha -->
    <button type="button" class="btn btn-{{ $classButton }} "
        wire:click="{!! $clickAction !!}">{{ $statusButton }}</button>

    {{-- @dump($statusButton, $clickAction) --}}
    <x-dialog-modal wire:model="form.open" maxWidth="md" id="order_modal_paid_info_{{ $order->id }}">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Información de pago</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">

                        @if (Auth::check() && Auth::user()->can('approved_tech.purchase') && $showDecision)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Decisión Técnico</label>
                                    <select class="form-select @error('form.password') is-invalid @enderror" required
                                        wire:model.live="form.approved_tech" wire:change="approveTech">
                                        <option value="0" selected>Seleccione un valor</option>
                                        <option value="1">Aprobar</option>
                                    </select>

                                </div>
                            </div>
                        @endif
                        @if (Auth::check() &&
                                Auth::user()->can('approved_account.purchase') &&
                                Auth::user()->hasRole('Contador') &&
                                $showDecision)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Decisión Contador</label>
                                    <select class="form-select @error('form.password') is-invalid @enderror" required
                                        wire:model.live="form.approved_account" wire:change="approveAccount">
                                        <option value="0" selected>Seleccione un valor</option>
                                        <option value="1">Aprobar</option>
                                    </select>

                                </div>

                            </div>
                        @endif

                        @if (
                            $order->paidInformation &&
                                !is_null($order->paidInformation->approved_account) &&
                                !is_null($order->paidInformation->approved_tech) &&
                                $order->paidInformation->approved_account &&
                                $order->paidInformation->approved_tech)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Quién pagó</label>
                                    <input class="form-control @error('form.name') is-invalid @enderror"
                                        placeholder="Inserte el nombre de quién realizó el pago" type="text" required
                                        wire:model.live="form.name" />
                                    @error('form.name')
                                        <div class="invalid-feedback {{ $errors->has('form.name') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Método de pago</label>
                                    <input class="form-control @error('form.method') is-invalid @enderror"
                                        placeholder="Inserte el método de pago utilizado" type="method" required
                                        wire:model.live="form.method" />
                                    @error('form.method')
                                        <div class="invalid-feedback {{ $errors->has('form.method') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Cómo hizo el pago</label>
                                    <input class="form-control @error('form.how') is-invalid @enderror"
                                        placeholder="Inserte cómo hizo el pago" type="how" required
                                        wire:model.live="form.how" />
                                    @error('form.how')
                                        <div class="invalid-feedback {{ $errors->has('form.how') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha de pago</label>
                                    <input class="form-control @error('form.date') is-invalid @enderror"
                                        placeholder="Insertar la fecha del pago" type="date" required
                                        wire:model.live="form.date" />
                                    @error('form.date')
                                        <div class="invalid-feedback {{ $errors->has('form.date') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">
            <div class="row w-100">
                <div class="col-6 ">
                    <button type="button" class="btn btn-light me-1"
                        wire:click="$set('form.open', false)">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-event"
                        wire:click="save">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

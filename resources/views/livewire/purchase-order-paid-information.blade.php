<div>
    <!-- Botón de estado -->
    <button type="button" class="btn btn-{{ $this->buttonClass }}"
        @if ($this->canClick) wire:click="$set('form.open', true)" @endif
        @if (!$this->canClick) disabled @endif>
        {{ $this->buttonStatus }}
    </button>

    <!-- Modal de información de pago -->
    <x-dialog-modal wire:model="form.open" maxWidth="md" id="order_modal_paid_info_{{ $order->id }}">

        <x-slot name="title"></x-slot>

        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title">Información de pago</h5>
                    <small class="text-muted">Estado actual: {{ $orderState->status_label }}</small>
                </div>

                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">

                        {{-- Sección de decisión técnica --}}
                        @if ($this->canShowTechDecision)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Decisión Técnico</label>
                                    <select class="form-select @error('form.approved_tech') is-invalid @enderror"
                                        required wire:model.live="form.approved_tech" wire:change="approveTech">
                                        <option value="0" selected>Seleccione un valor</option>
                                        <option value="1">Aprobar</option>
                                    </select>
                                    @error('form.approved_tech')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Sección de decisión contable --}}
                        @if ($this->canShowAccountDecision)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Decisión Contador</label>
                                    <select class="form-select @error('form.approved_account') is-invalid @enderror"
                                        required wire:model.live="form.approved_account" wire:change="approveAccount">
                                        <option value="0" selected>Seleccione un valor</option>
                                        <option value="1">Aprobar</option>
                                    </select>
                                    @error('form.approved_account')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Sección de información de pago --}}
                        @if ($this->canShowPaymentInfo)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Quién pagó</label>
                                    <input class="form-control @error('form.name') is-invalid @enderror"
                                        placeholder="Inserte el nombre de quién realizó el pago" type="text"
                                        wire:model.live="form.name" />
                                    @error('form.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Método de pago</label>
                                    <input class="form-control @error('form.method') is-invalid @enderror"
                                        placeholder="Inserte el método de pago utilizado" type="text"
                                        wire:model.live="form.method" />
                                    @error('form.method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Cómo hizo el pago</label>
                                    <input class="form-control @error('form.how') is-invalid @enderror"
                                        placeholder="Inserte cómo hizo el pago" type="text"
                                        wire:model.live="form.how" />
                                    @error('form.how')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Fecha de pago</label>
                                    <input class="form-control @error('form.date') is-invalid @enderror"
                                        placeholder="Insertar la fecha del pago" type="date"
                                        wire:model.live="form.date" />
                                    @error('form.date')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                <div class="col-6">
                    <button type="button" class="btn btn-light me-1" wire:click="$set('form.open', false)">
                        Cancelar
                    </button>

                    @if ($this->canShowPaymentInfo)
                        <button type="button" class="btn btn-success" wire:click="save">
                            Guardar
                        </button>
                    @endif
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

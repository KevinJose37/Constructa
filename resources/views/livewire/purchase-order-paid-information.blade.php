<div>
    <!-- Div a la derecha -->
    <button type="button" class="btn btn-{{ $order->payment_info_id == null ? 'warning' : 'success' }} "
        wire:click="$set('form.open', true)">{{ $order->payment_info_id == null ? 'Sin pagar' : 'Pagado' }}</button>

    <x-dialog-modal wire:model="form.open" maxWidth="md" id="order_modal_{{ $order->id }}">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Información de pago</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">
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

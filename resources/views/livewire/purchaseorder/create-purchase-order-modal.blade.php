<div>

    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
            <!-- Div a la derecha -->
            <button type="button" class="btn btn-primary" wire:click="$set('orderForm.open', true)">
                Añadir nuevo item
            </button>
        </li>

    </ol>
    <x-dialog-modal wire:model="orderForm.open" maxWidth="md" id="new-item" :scrollable="true">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Información del item</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="col" wire:ignore>
                                    <select name="item-select" id="item-select" class="form-select select2">
                                        <option disabled selected>Seleccione una opción...</option>
                                        @foreach ($items as $item)
                                            <option value='{{ $item->id }}'>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if (!is_null($orderForm->unit))
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Unidad de medida</label>
                                    <input class="form-control" type="text" name="unit" id="unit" disabled
                                        wire:model.live="orderForm.unit" />
                                    @error('orderForm.unit')
                                        <div class="invalid-feedback {{ $errors->has('orderForm.unit') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        @if (!is_null($orderForm->code))
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Código</label>
                                    <input class="form-control" type="text" name="code" id="code" disabled
                                        wire:model.live="orderForm.code" />
                                    @error('orderForm.code')
                                        <div class="invalid-feedback {{ $errors->has('orderForm.code') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if ($orderForm->currentSelect)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Precio unitario</label>
                                    <input type="text" class="form-control form-control-sm" id="priceUnit"
                                        wire:model.lazy="orderForm.priceUnit" placeholder="Ingrese el precio"
                                        wire:keydown="setTotal">
                                    @error('orderForm.priceUnit')
                                        <div
                                            class="invalid-feedback {{ $errors->has('orderForm.priceUnit') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Cantidad</label>
                                    <input type="number" class="form-control form-control-sm" id="quantityItem"
                                        wire:model.live="orderForm.quantityItem" placeholder="Ingrese la cantidad"
                                        wire:keydown="setTotal">
                                    @error('orderForm.quantityItem')
                                        <div
                                            class="invalid-feedback {{ $errors->has('orderForm.quantityItem') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">IVA</label>
                                    <input type="number" class="form-control form-control-sm" id="currentIva"
                                        wire:model.live="orderForm.currentIva" placeholder="Ingrese el IVA"
                                        wire:keydown="setTotal">
                                    @error('orderForm.currentIva')
                                        <div
                                            class="invalid-feedback {{ $errors->has('orderForm.currentIva') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        @if (!is_null($orderForm->totalPrice) && $orderForm->totalPrice != 0)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Precio calculado</label>
                                    <input class="form-control" type="text" name="totalPrice" id="totalPrice"
                                        disabled wire:model.live="orderForm.totalPrice" />
                                    @error('orderForm.totalPrice')
                                        <div
                                            class="invalid-feedback {{ $errors->has('orderForm.totalPrice') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Precio calculado con IVA</label>
                                    <input class="form-control" type="text" name="totalPriceIva" id="totalPriceIva"
                                        disabled wire:model.live="orderForm.totalPriceIva" />
                                    @error('orderForm.totalPriceIva')
                                        <div
                                            class="invalid-feedback {{ $errors->has('orderForm.totalPriceIva') ? 'd-block' : '' }}">
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
                        wire:click="$set('orderForm.open', false)">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-event"
                        wire:click="save">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#item-select').select2({
                dropdownParent: $('#modal-id-new-item')
            });

            $('#item-select').on('change', function(e) {
                @this.set('orderForm.currentSelect', e.target.value);
                @this.call('getUnit');
            });

            $(document).on("keyup", `#priceUnit`, (function(e) {
                $(e.target).val(function(index, value) {

                    let formattedValue = $(e.target).val(function(index, value) {
                        return value.replace(/\D/g, "").replace(/^0+/,
                            "").replace(/^(\d+)(\d{2})$/, "$1,$2").replace(
                            /\B(?=(\d{3})+(?!\d))/g, ".");
                    }).val();

                    @this.set('orderForm.priceUnit', formattedValue);

                    return formattedValue;
                });
            }));
        });
    </script>
@endpush

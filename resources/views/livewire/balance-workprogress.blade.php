<div>
    <button type="button" wire:click="$set('open', true)" class="btn btn-info mt-2">
        <span><i class="ri-settings-line"></i></span>
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md" id="{{ $detail->id }}-balance-details">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h3>Balanceo de mayores y menores</h3>
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="actionSelect">¿Qué deseas hacer?</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 d-flex justify-content-center">
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <button type="button" class="btn btn-success" wire:click="$set('action', 'up')"
                                            @disabled($action == 'up')>
                                            <i class="ri-arrow-up-line"></i> Aumentar
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <button type="button" class="btn btn-danger"
                                            wire:click="$set('action', 'down')" @disabled($action == 'down')>
                                            <i class="ri-arrow-down-line"></i> Disminuir
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if ($action)
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <label class="form-label" for="quantity">Cantidad a
                                            {{ $action === 'up' ? 'aumentar' : 'disminuir' }}:</label>
                                        <input type="number" class="form-control" id="quantity" wire:model="quantity">
                                        @error('quantity')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mt-3">
                                        @error('action')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <button type="button" class="btn btn-primary w-100"
                                            wire:click="applyBalance">Aplicar</button>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="row w-100">
                <div class="col-6 ">
                    <button type="button" class="btn btn-light me-1" wire:click="$set('open', false)">Cerrar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

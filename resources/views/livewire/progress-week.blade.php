<div>
    <button type="button" wire:click="$set('open', true)" class="btn btn-info mt-2">
        <span><i class="ri-bar-chart-fill"></i></span>
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md" id="{{ $detail->id }}-balance-details">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h3>Ingresar avance semana {{ $weekModel?->string_date }}</h3>
                        <div class="container">
                            <div class="row">
                                @if (!$week || (is_array($week) && count($week) > 1))
                                    <div class="col-12 mt-3">
                                        <label class="form-label" for="week-select-progres">Semana de avance:</label>
                                        <select name="week-select-progress" id="week-select-progress"
                                            class="form-select" wire:model="altWeek">
                                            <option value="">Selecciona una semana</option>
                                            @foreach ($weeksSelect as $week)
                                                <option value="{{ $week->id }}">Semana
                                                    {{ $week->number_week }} - {{ $week->string_date }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label class="form-label" for="quantity">Cantidad de avance:</label>
                                    <input type="number" class="form-control" id="quantity" wire:model="quantity">
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    @error('action')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="modal-footer d-flex justify-content-end w-100">
                <button type="button" class="btn btn-light me-2" wire:click="$set('open', false)">
                    Cerrar
                </button>
                <button type="button" class="btn btn-success" wire:click="createAdvance">
                    Guardar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

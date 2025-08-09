<div>
    <button type="button" wire:click="$set('open', true)" class="btn btn-info mt-2">
        <span><i class="ri-camera-fill"></i></span>
    </button>
    <x-dialog-modal wire:model="open" maxWidth="md" id="progress-images-week">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            @if ($mode == 'UPLOAD')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <h3>Ingresar evidencia semana {{ $weekModel?->string_date }}</h3>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <label class="form-label" for="week-select-progres">Semana de avance:</label>
                                        <select name="week-select-progress" id="week-select-progress"
                                            class="form-select" wire:model.live="altWeek">
                                            <option value="">Selecciona una semana</option>
                                            @foreach ($weeksSelect as $week)
                                                <option value="{{ $week->id }}">Semana
                                                    {{ $week->number_week }} - {{ $week->string_date }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            {{-- Si se habilitó la flag de guardar o estamos en modo vista --}}
            @if ($canSave || $mode == 'VIEW')
                <div class="mt-4">
                    <h5 class="mb-3">Evidencia fotográfica (Imágenes)</h5>

                    <!-- Estado de carga -->
                    <div wire:loading wire:target="saveImages,newImages" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">
                            <span wire:loading wire:target="newImages">Procesando imágenes seleccionadas...</span>
                            <span wire:loading wire:target="saveImages">Subiendo imágenes...</span>
                        </p>
                    </div>

                    {{-- Si estamos en modo carga --}}
                    @if ($mode == 'UPLOAD')
                        <div wire:loading.remove wire:target="saveImages,newImages">
                            <form wire:submit.prevent="saveImages">
                                <input type="file" accept="image/*" wire:model="newImages" multiple
                                    class="form-control mb-3" />

                                @if ($tempImages)
                                    <div class="row">
                                        @foreach ($tempImages as $index => $image)
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="position-relative border rounded p-2">
                                                    <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded"
                                                        alt="preview">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                        wire:click="removeTempImage({{ $index }})"
                                                        wire:loading.attr="disabled">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($tempImages && $weekModel)
                                    <button type="submit" class="btn btn-primary mt-2" wire:loading.attr="disabled">
                                        <span wire:loading.remove>Subir imágenes</span>
                                        <span wire:loading>
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Subiendo...
                                        </span>
                                    </button>
                                @endif

                                @error('newImages')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </form>
                        </div>
                    @endif
                    @if ($savedImages && $savedImages->count())
                        @foreach ($savedImages->groupBy('week.string_date') as $weekDate => $images)
                            @php
                                $numberWeek = $images->first()->week->number_week ?? null;
                            @endphp
                            <div class="mt-4">
                                <h6>Semana {{ $numberWeek }}: {{ $weekDate }}</h6>
                                <div class="row">
                                    @if ($images->count())
                                        @foreach ($images as $image)
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="position-relative border rounded p-2">
                                                    <a href="{{ Storage::url($image->image_path) }}" target="_blank">
                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                            class="img-fluid rounded" alt="evidencia">
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                        wire:click="deleteSavedImage({{ $image->id }})"
                                                        wire:loading.attr="disabled">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    @else
                        @if (!$tempImages)
                            <span>No tiene imágenes regisradas</span>
                        @endif

                    @endif
                </div>
            @endif


        </x-slot>
        <x-slot name="footer">
            <div class="modal-footer d-flex justify-content-end w-100">
                <button type="button" class="btn btn-light me-2" wire:click="closeModal">
                    Cerrar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

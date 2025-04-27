<div>
    @if (!$onlyView)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fs-16 mb-3">Subir adjuntos para la orden de compra
                    {{ $orderName ? $orderName : '' }}</h5>
                <form wire:submit.prevent="saveAttachments">
                    <div class="fallback">
                        <input name="file" type="file" wire:model="attachments" multiple />
                    </div>
                    @if ($attachments)
                        <div class="mt-3">
                            <h6>Archivos temporales seleccionados:</h6>
                            @foreach ($attachments as $attachment)
                                <div class="card mb-1 shadow-none border">
                                    <div class="p-2">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded">
                                                        {{ strtoupper(pathinfo($attachment->getClientOriginalName(), PATHINFO_EXTENSION)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col ps-0">
                                                <p class="text-muted fw-bold">{{ $attachment->getClientOriginalName() }}
                                                </p>
                                                <p class="mb-0">
                                                    {{ number_format($attachment->getSize() / 1024, 2) }} KB
                                                </p>
                                            </div>
                                            <div class="col-auto">
                                                <a wire:click="removeTempFile({{ $loop->index }})"
                                                    class="btn btn-link text-danger">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($invoiceHeaderId)
                        <button type="submit" class="btn btn-primary mt-3">Cargar adjunto/s</button>
                    @endif
                </form>

                @error('attachments')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

                @if (session()->has('message'))
                    <div class="alert alert-success mt-3">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fs-16 mb-3">Lista de adjuntos</h5>
            <div>
                @if ($existingAttachments->isEmpty())
                    <div class="alert alert-info">
                        No hay adjuntos disponibles.
                    </div>
                @else
                    @foreach ($existingAttachments as $attachment)
                        <div class="card mb-1 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded">
                                                {{ strtoupper(pathinfo($attachment->filename, PATHINFO_EXTENSION)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col ps-0">
                                        <a href="{{ route('download.attachment', ['id' => $attachment->id]) }}"
                                            class="text-muted fw-bold" download="{{ $attachment->filename }}"
                                            target="_blank">
                                            {{ $attachment->filename }}
                                        </a>
                                        @php
                                            $fileSize = \Storage::disk('public')->exists($attachment->path)
                                                ? number_format(
                                                        \Storage::disk('public')->size($attachment->path) / 1024,
                                                        2,
                                                    ) . ' KB'
                                                : 'Size not available';
                                        @endphp
                                        <p class="mb-0">{{ $fileSize }}</p>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Button -->
                                        <a href="{{ route('download.attachment', ['id' => $attachment->id]) }}"
                                            class="text-muted fw-bold" download="{{ $attachment->filename }}"
                                            target="_blank">
                                            <i class="ri-download-line"></i>
                                        </a>
                                        @if (!$onlyView)
                                            <button wire:click="deleteAttachment({{ $attachment->id }})"
                                                class="btn btn-link text-danger">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mt-3">
                {{ $existingAttachments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Livewire alert component -->
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('alertConfirmation', (data) => {
            if (confirm(data.message)) {
                Livewire.emit(data.emit, data.id);
            }
        });
    });
</script>

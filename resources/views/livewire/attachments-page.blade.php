<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fs-16 mb-3">Attachments for Order #{{ $invoiceHeaderId }}</h5>
            
            <!-- Formulario para seleccionar y cargar archivos -->
            <form wire:submit.prevent="saveAttachments">
                <input type="file" wire:model="attachments" multiple />
                <button type="submit" class="btn btn-primary mt-3">Upload Files</button>
            </form>
            
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            @if (session()->has('message'))
                <div class="alert alert-success mt-3">
                    {{ session('message') }}
                </div>
            @endif
            
            <h5 class="mt-3">Existing Attachments</h5>
            <ul>
                @foreach ($existingAttachments as $attachment)
                    <li>
                        {{ $attachment->filename }}
                        <button wire:click="deleteAttachment({{ $attachment->id }})" class="btn btn-link text-danger">Delete</button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

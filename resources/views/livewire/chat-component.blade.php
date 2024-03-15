<div>
    <div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="float-end">
                <select wire:model="selectedProjectId" wire:change="loadMessages" class="form-select form-select-sm">
    <option value="">Seleccionar proyecto</option>
    @foreach($projects as $project)
        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
    @endforeach
</select>

                </div>

                <h4 class="mb-4 mt-0 fs-16">Proyecto ejemplo</h4>

                <div id="messages-container">
                    @if($messages)
                        @foreach($messages as $message)
                            <div class="d-flex align-items-start mt-3">
                                <img class="me-2 rounded-circle" src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32" />
                                <div class="w-100">
                                    <h5 class="mt-0">{{ $message->user->name }} <small class="text-muted float-end">{{ $message->created_at }}</small></h5>
                                    {{ $message->message }}
                                    <br />
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No hay mensajes disponibles para este proyecto.</p>
                    @endif
                </div>

                <form wire:submit.prevent="saveMessage" class="comment-area-box">
    <input type="hidden" wire:model="selectedProjectId" name="projectId">
    <textarea wire:model="newMessage" rows="3" class="form-control border-0 resize-none" placeholder="Agregar comentario"></textarea>
    <div class="p-2 bg-light d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-sm btn-success"><i class="ri-send-plane-2 me-1"></i>ENVIAR</button>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

</div>

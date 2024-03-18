<div>
    
<ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
        <!-- Div a la derecha -->
        <button type="button" class="btn btn-primary" wire:click="$set('form.open', true)">
            Crear un nuevo proyecto
        </button>
        </li>

</ol>
    <x-dialog-modal wire:model="form.open" maxWidth="md">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Crear nuevo proyecto</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Nombre del proyecto</label>
                                <input class="form-control" placeholder="Insertar nombre de proyecto" type="text"
                                    name="project_name" id="project-name" required
                                    wire:model.live="form.name_project" />
                                @error('form.name_project')
                                    <div class="invalid-feedback {{ $errors->has('form.name_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Descripción del proyecto</label>
                                <input class="form-control" placeholder="Ingresar descripción del proyecto"
                                    type="text" name="project_description" id="project-description" required
                                    wire:model.live="form.description_project" />
                                @error('form.description_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('form.description_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Estado del proyecto</label>
                                <select class="form-select" name="project_status_id" id="project-status" required
                                    wire:model.live="form.status_project">
                                    <option value="-" selected>Seleccione un valor</option>
                                    @foreach ($projectstatus as $status)
                                        <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                                @error('form.status_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('form.status_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" name="project_start_date"
                                    id="project-start-date" required wire:model.live="form.date_start_project">
                                @error('form.date_start_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('form.date_start_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Fecha estimada de finalización</label>
                                <input type="date" class="form-control" name="project_estimated_end"
                                    id="project-estimated-end" required wire:model.live="form.date_end_project">
                                @error('form.date_end_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('form.date_end_project') ? 'd-block' : '' }}">
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
                        wire:click="$set('form.open', false)" >Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-event"
                        wire:click="save">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>


@push('js')
    <script>
    </script>
@endpush


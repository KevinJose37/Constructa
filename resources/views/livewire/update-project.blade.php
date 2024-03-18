<div>
    <a href="javascript:void(0);" wire:click="$set('formup.open', true)"><i class="ri-pencil-fill"></i></a>

    <x-dialog-modal wire:model="formup.open" maxWidth="md" id="{{ $projectUpdate->id }}-update">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Editar nuevo proyecto</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Nombre del proyecto</label>
                                <input class="form-control" placeholder="Insertar nombre de proyecto" type="text"
                                    name="project_name" id="project-name" required
                                    wire:model.live="formup.name_project" />
                                @error('formup.name_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('formup.name_project') ? 'd-block' : '' }}">
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
                                    wire:model.live="formup.description_project" />
                                @error('formup.description_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('formup.description_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Estado del proyecto</label>
                                <select class="form-select" name="project_status_id" id="project-status" required
                                    wire:model.live="formup.status_project">
                                    <option value="-" selected>Seleccione un valor</option>
                                    @foreach ($projectstatus as $status)
                                        <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                                @error('formup.status_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('formup.status_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" name="project_start_date"
                                    id="project-start-date" required wire:model.live="formup.date_start_project">
                                @error('formup.date_start_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('formup.date_start_project') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Fecha estimada de finalización</label>
                                <input type="date" class="form-control" name="project_estimated_end"
                                    id="project-estimated-end" required wire:model.live="formup.date_end_project">
                                @error('formup.date_end_project')
                                    <div
                                        class="invalid-feedback {{ $errors->has('formup.date_end_project') ? 'd-block' : '' }}">
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
                        wire:click="$set('formup.open', false)">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-event"
                        wire:click="edit">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>

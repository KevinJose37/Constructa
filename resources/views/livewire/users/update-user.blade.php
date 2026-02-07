<div>
    <a href="javascript:void(0);" wire:click="$set('formup.open', true)"><i class="ri-pencil-fill"></i></a>
    <x-dialog-modal wire:model="formup.open" maxWidth="md" id="{{ $userUpdate->id }}-update-user">
        <x-slot name="title"></x-slot>
        <x-slot name="content">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 border-bottom-0">
                    <h5 class="modal-title" id="modal-title">Crear nuevo usuario</h5>
                </div>
                <div class="modal-body px-4 pb-4 pt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Nombre del usuario</label>
                                <input class="form-control @error('formup.name') is-invalid @enderror"
                                    placeholder="Insertar nombre del usuario" type="text" required
                                    wire:model.live="formup.name" />
                                @error('formup.name')
                                    <div class="invalid-feedback {{ $errors->has('formup.name') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Nombre completo del usuario</label>
                                <input class="form-control @error('formup.fullname') is-invalid @enderror"
                                    placeholder="Insertar nombre completo del usuario" type="text" required
                                    wire:model.live="formup.fullname" />
                                @error('formup.fullname')
                                    <div class="invalid-feedback {{ $errors->has('formup.fullname') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">E-mail del usuario</label>
                                <input class="form-control @error('formup.email') is-invalid @enderror"
                                    placeholder="Inserte el e-mail del usuario" type="email" required
                                    wire:model.live="formup.email" />
                                @error('formup.email')
                                    <div class="invalid-feedback {{ $errors->has('formup.email') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Contraseña del usuario</label>
                                <input class="form-control @error('formup.password') is-invalid @enderror"
                                    placeholder="Contraseña del usuario" type="text" required
                                    wire:model.live="formup.password" />
                                @error('formup.password')
                                    <div class="invalid-feedback {{ $errors->has('formup.password') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        @can('change.rol.users')
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Rol del usuario</label>
                                    <select class="form-select @error('formup.password') is-invalid @enderror" required
                                        wire:model.live="formup.rol_id">
                                        <option value="-" selected>Seleccione un valor</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('formup.rol_id')
                                        <div class="invalid-feedback {{ $errors->has('formup.rol_id') ? 'd-block' : '' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endcan

                    </div>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">
            <div class="row w-100">
                <div class="col-6 ">
                    <button type="button" class="btn btn-light me-1"
                        wire:click="$set('formup.open', false)">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-edit-event"
                        wire:click="edit">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>


@push('js')
    <script></script>
@endpush

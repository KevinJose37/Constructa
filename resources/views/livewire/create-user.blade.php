<div>

    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
            <!-- Div a la derecha -->
            <button type="button" class="btn btn-primary" wire:click="$set('form.open', true)">
                Crear nuevo usuario
            </button>
        </li>

    </ol>
    <x-dialog-modal wire:model="form.open" maxWidth="md" id="create-user-modal">
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
                                <input class="form-control @error('form.name') is-invalid @enderror" placeholder="Insertar nombre del usuario" type="text"
                                    required wire:model.live="form.name" />
                                @error('form.name')
                                    <div class="invalid-feedback {{ $errors->has('form.name') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Nombre completo del usuario</label>
                                <input class="form-control @error('form.fullname') is-invalid @enderror" placeholder="Insertar nombre completo del usuario"
                                    type="text" required wire:model.live="form.fullname" />
                                @error('form.fullname')
                                    <div class="invalid-feedback {{ $errors->has('form.fullname') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">E-mail del usuario</label>
                                <input class="form-control @error('form.email') is-invalid @enderror" placeholder="Inserte el e-mail del usuario" type="email"
                                    required wire:model.live="form.email" />
                                @error('form.email')
                                    <div class="invalid-feedback {{ $errors->has('form.email') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Contraseña del usuario</label>
                                <input class="form-control @error('form.password') is-invalid @enderror" placeholder="Contraseña del usuario" type="text"
                                    required wire:model.live="form.password" />
                                @error('form.password')
                                    <div class="invalid-feedback {{ $errors->has('form.password') ? 'd-block' : '' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label">Rol del usuario</label>
                                <select class="form-select @error('form.password') is-invalid @enderror" required
                                    wire:model.live="form.rol_id">
                                    <option value="-" selected>Seleccione un valor</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.rol_id')
                                    <div
                                        class="invalid-feedback {{ $errors->has('form.rol_id') ? 'd-block' : '' }}">
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
                        wire:click="$set('form.open', false)">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btn-save-event"
                        wire:click="save">Guardar</button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>


@push('js')
    <script></script>
@endpush

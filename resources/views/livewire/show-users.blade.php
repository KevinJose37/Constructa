<div>
    <x-table>
        <div class="row w-100">
            <div class="col-lg-6 w-25">
                <!-- Div a la izquierda -->
                <div class="input-group">
                    <button class="btn btn-primary"><i class="ri-search-line"></i></button>
                    <input type="text" name="filter" class="form-control" placeholder="Buscar usuario"
                        wire:model.live="search">
                    <button class="btn" id="clear-filter" wire:click="$set('search', '')"><i
                            class="ri-close-line"></i></button>
                </div>
            </div>
            @can('store.project')
                <livewire:create-user></livewire:create-user>
            @endcan
        </div>
        <table class="table table-striped table-centered mb-0">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Nombre del usuario</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($users) --}}
                @foreach ($users as $user)
                    <tr id="userRow_{{ $user->id }}">
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->rol->name }}</td>
                        <td style="display: flex; align-items: center;">
                            <livewire:show-projects-user :$user
                                :wire:key="'show-projects-' . $user->id"></livewire:show-projects-user>
                            @can('delete.users')
                                <a href="#" class="text-reset fs-19 px-1 delete-user-btn"
                                    wire:click.prevent="destroyAlertUser({{ $user->id }}, '{{ $user->fullname }}')">
                                    <i class="ri-delete-bin-2-line"></i></a>
                            @endcan
                            @can('update.users')
                                <livewire:update-user :$user :wire:key="'update-user-' . $user->id"></livewire:update-user>    
                            @endcan
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($users->count() > 1)
            <!-- Solo muestra los enlaces de paginación si hay más de un usuario -->
            {{ $users->links(data: ['scrollTo' => false]) }}
        @endif
    </x-table>
</div>

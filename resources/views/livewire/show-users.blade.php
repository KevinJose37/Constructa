<div>
    <x-page-title title="Tabla de Usuarios"></x-page-title>

    <x-table>
        <div class="row g-2 mb-2">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group w-100">
                    <button class="btn btn-primary">
                        <i class="ri-search-line"></i>
                    </button>
                    <input type="text"
                        name="filter"
                        class="form-control"
                        placeholder="Buscar usuario"
                        wire:model.live.debounce.300ms="search">
                    <button class="btn btn-outline-secondary"
                        id="clear-filter"
                        wire:click="$set('search', '')">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                @can('store.users')
                <livewire:create-user></livewire:create-user>
                @endcan
            </div>
        </div>

        <table class="table table-striped table-centered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nombre completo</th>
                    <th>Nombre del usuario</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr id="userRow_{{ $user->id }}">
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->rol->name ?? 'Sin rol' }}</td>
                    <td class="d-flex align-items-center gap-1">
                        @can('assign.user.project')
                        <livewire:show-projects-user :$user :wire:key="'show-projects-' . $user->id" />
                        @endcan

                        @can('delete.users')
                        <a href="#"
                            class="text-reset fs-19 px-1 delete-user-btn"
                            wire:click.prevent="destroyAlertUser({{ $user->id }}, '{{ $user->fullname }}')">
                            <i class="ri-delete-bin-2-line"></i>
                        </a>
                        @endcan
                        @can('update.users')
                        <livewire:update-user :$user :wire:key="'update-user-' . $user->id" />
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if ($users->count() > 1)
        {{ $users->links(data: ['scrollTo' => false]) }}
        @endif
    </x-table>
</div>
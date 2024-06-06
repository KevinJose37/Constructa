<div>
    <x-page-title title="Progreso de obra"></x-page-title>
    <x-table>
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
              
            </tbody>
        </table>
        {{-- @if ($users->count() > 1) --}}
            <!-- Solo muestra los enlaces de paginación si hay más de un usuario -->
            {{-- {{ $users->links(data: ['scrollTo' => false]) }} --}}
        {{-- @endif --}}
    </x-table>
</div>

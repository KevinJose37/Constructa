<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;

class ShowUsers extends Component
{
    use WithPagination;
    public $search = "";

    #[On('userRefresh')]
    public function render(UserServices $userServices)
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador') || $user->hasRole('Gerente')) {
            $users = $userServices->getAllPaginate($this->search);
        } else {
            $users = collect([$user]);
        }

        return view('livewire.show-users',  compact('users'));
    }

    #[On('destroy-user')]
    public function destroy($id, UserServices $userServices)
    {
        $deleteProject = $userServices->Delete($id);
        if ($deleteProject === true) {
            $this->dispatch('userRefresh')->to(ShowUsers::class);
            $this->dispatch('alert', type: 'success', title: 'Usuarios', message: "Se eliminó correctamente el usuario");
            return;
        }

        $message = $deleteProject['message'];
        $this->dispatch('alert', type: 'error', title: 'Usuarios', message: $message);
    }

    public function destroyAlertUser($id, $name)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            type: 'warning',
            title: 'Usuario',
            message: "¿estás seguro de eliminar el usuario {$name}?",
            emit: 'destroy-user',
        );
    }
}

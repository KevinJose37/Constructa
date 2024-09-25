<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UpdateUserForm;
use App\Models\User;
use App\Services\UserServices;
use Livewire\Component;

class UpdateUser extends Component
{
    public User $userUpdate;
    public UpdateUserForm $formup;

    public function mount(User $user)
    {
        $this->userUpdate = $user;
        $this->formup->name = $user->name;
        $this->formup->fullname = $user->fullname;
        $this->formup->email = $user->email;
        $this->formup->rol_id = $user->rol_id;
    }

    public function edit(UserServices $userServices)
    {
        $this->formup->validate();
        $data['name'] = $this->formup->name;
        $data['fullname'] = $this->formup->fullname;
        $data['email'] = $this->formup->email;
        $data['rol_id'] = $this->formup->rol_id;
        $data['password'] = $this->formup->password;
        $responseSave = $userServices->Update($this->userUpdate->id, $data);
        if (!is_array($responseSave) && !isset($responseSave['success'])) {
            $this->formup->reset();
            $this->mount($userServices->getById($this->userUpdate->id));
            $this->dispatch('userRefresh')->to(ShowUsers::class);
            $this->dispatch('alert', type: 'success', title: 'Usuarios', message: "Se editó correctamente el usuario {$this->userUpdate->name}");
            return;
        }


        $this->dispatch('alert', type: 'error', title: 'Usuarios', message: "Ocurrió un error al editar el usuario {$this->userUpdate->name}. {$responseSave['message'] }");
    }
    

    public function render(UserServices $userService)
    {
        $roles = $userService->getRolesUsers();
        return view('livewire.users.update-user', compact('roles'));
    }
}

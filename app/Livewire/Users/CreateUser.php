<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\CreateUserForm;
use App\Services\UserServices;
use Livewire\Component;

class CreateUser extends Component
{

    public CreateUserForm $form;

    public function save(UserServices $userService){
        $this->form->validate();
        $data['name'] = $this->form->name;
        $data['fullname'] = $this->form->fullname;
        $data['email'] = $this->form->email;
        $data['rol_id'] = $this->form->rol_id;
        $data['password'] = $this->form->password;
        $responseSave = $userService->Add($data);
        if(!is_array($responseSave) && !isset($responseSave['success'])){
            $this->dispatch('userRefresh')->to(ShowUsers::class);
            $this->dispatch('alert', type: 'success', title: 'Usuarios',message: "Se creó correctamente el usuario {$this->form->fullname}");
            $this->form->reset();
            return;
        }
        $this->dispatch('alert', type: 'error', title: 'Usuarios',message: "Ocurrió un error al crear el usuario {$this->form->fullname}");
    }

    public function render(UserServices $userService)
    {
        $roles = $userService->getRolesUsers();
        return view('livewire.users.create-user', compact('roles'));
    }
}

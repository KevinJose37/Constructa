<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class UpdateUserForm extends Form
{
    public $open = false;

    #[Validate('required', message: 'Ingrese un valor para el nombre del usuario')]
    #[Validate('min:3', message: 'El nombre es demasiado corto')]
    #[Validate('regex:/^\S+$/', message: 'No debe contener espacios')]
    public $name = "";

    #[Validate('required', message: 'Ingrese un valor para el nombre del usuario')]
    #[Validate('min:6', message: 'El nombre completo es demasiado corta')]
    public $fullname = "";

    #[Validate('required', message: 'Ingrese un e-mail')]
    #[Validate('email', message: 'E-mail no válido')]
    // #[Validate('unique:' . User::class . ',email,ignore:user', message: 'E-mail ya registrado')]
    #[Validate('regex:/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,4}$/', message: 'Formato de e-mail inválido')]
    public $email = "";

    #[Validate('required', message: 'El rol es obligatorio')]
    public $rol_id = "";

    // #[Validate('required', message: 'La contraseña es obligatoria')]
    #[Validate('regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/', message: 'Debe contener al menos 1 letra mayúscula, 1 letra minúscula y 1 número')]
    public $password = "";

}

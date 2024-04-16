<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PurchaseOrderPaidInformationForm extends Form
{
    public $open = false;


    #[Validate('required', message: 'Ingrese un valor para el nombre')]
    #[Validate('min:3', message: 'El nombre es demasiado corto')]
    public $name = "";

    #[Validate('required', message: 'Ingrese un valor para el método de pago')]
    #[Validate('min:3', message: 'El método de pago es demasiado corto')]
    public $method = "";

    #[Validate('required', message: 'Ingrese un valor de cómo hizo el pago')]
    #[Validate('min:3', message: 'Cómo hizo el pago es demasiado corto')]
    public $how = "";

    #[Validate('required', message: 'Ingrese una fecha')]
    #[Validate('date', message: 'Ingrese una fecha váldia')]
    public $date = "";
}

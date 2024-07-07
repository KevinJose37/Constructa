<?php

namespace App\Livewire\Forms;

use App\Models\Item;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreatePurchaseOrderModalForm extends Form
{
    public $open = false;
    public $currentSelect;

    #[Validate('required', message: 'La cantidad es requerida')]
    // #[Validate('min:1', message: 'El valor mínimo es 1')]
    #[Validate('numeric', message: 'Debe ser un valor numérico')]
    public $currentIva = 19;
    
    public $unit;
    public $code;
    public $totalPriceIva;

    #[Validate('required', message: 'La cantidad es requerida')]
    #[Validate('min:1', message: 'El valor mínimo es 1')]
    #[Validate('numeric', message: 'Debe ser un valor numérico')]
    public $quantityItem;

    #[Validate('required', message: 'El precio unitario es requerido')]
    #[Validate('regex:/^\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?$/', message: 'Debe ser un valor numérico')]
    #[Validate('min:1', message: 'El valor mínimo es 1')]
    public $priceUnit;
    
    public $totalPrice;

    public ?Item $itemSelect;
}

<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use App\Models\PaymentSupport;
use App\Services\ItemService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\PurchaseOrderForm;
use App\Models\InvoiceHeader;

use Illuminate\Support\Facades\Log;

class CreatePurchaseOrder extends Component
{

    public $currentSelect;
    public $selectedItems = [];
    public PurchaseOrderForm $formPurchase;
    public $currentDate;
    public $contractor_name;
    public $contractor_nit;
    public $responsible_name;
    public $company_name;
    public $company_nit;
    public $phone;
    public $material_destination;
    public $payment_method_id;
    public $bank_name;
    public $account_type;
    public $account_number;
    public $support_type_id;

    public $lastInvoiceId;




    public function mount($id)
    {
        $this->currentDate = now()->format('d/m/y');
        $this->lastInvoiceId = InvoiceHeader::max('id');

    }


    #[Layout('layouts.app')]
    #[Title('Crear orden de compra')]
    #[On('itemRefresh')]
    public function render(ItemService $itemService)
    {
        $paymentMethods = PaymentMethod::all();
        $paymentSupport = PaymentSupport::all();
        $items = $itemService->getAll();

        return view('livewire.create-purchase-order', compact('paymentMethods', 'paymentSupport','items'));
    }

    public function store(ItemService $itemService)
    {

        $this->formPurchase->validateOnly('quantityItem', [
            'quantityItem' => 'required|numeric|min:1',
        ], [
            'quantityItem.required' => 'La cantidad es obligatoria.',
            'quantityItem.numeric' => 'La cantidad debe ser un número.',
            'quantityItem.min' => 'La cantidad mínima debe ser 1.',
        ]);

        if ($this->currentSelect === null || empty($this->currentSelect)) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'Ingrese un valor válido');
            return;
        }

        $currentItem = $itemService->getById($this->currentSelect)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item actual');
            return;
        }

        $existingItemIndex = array_search($this->currentSelect, array_column($this->selectedItems, 'id'));
        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]["quantity"] += $this->formPurchase->quantityItem; // Utilizando el mutador
            $this->selectedItems[$existingItemIndex]["totalPrice"] = $this->selectedItems[$existingItemIndex]["price"] * $this->selectedItems[$existingItemIndex]["quantity"];
            $this->selectedItems[$existingItemIndex]["iva"] = number_format(Helpers::calculateIva($currentItem["price"]), 2, '.', ',');
            $this->selectedItems[$existingItemIndex]["priceIva"] = number_format(Helpers::calculateTotalIva($currentItem["price"]), 2, '.', ',');
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = number_format(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["totalPrice"]), 2, '.', ',');
        } else {
            $currentItem["quantity"] = $this->formPurchase->quantityItem;
            $currentItem["totalPrice"] = number_format($currentItem["price"] * $currentItem["quantity"], 2, '.', ',');
            $currentItem["iva"] = number_format(Helpers::calculateIva($currentItem["price"]), 2, '.', ',');
            $currentItem["priceIva"] = number_format(Helpers::calculateTotalIva($currentItem["price"]), 2, '.', ',');
            $currentItem["totalPriceIva"] = number_format(Helpers::calculateTotalIva($currentItem["totalPrice"]), 2, '.', ',');
            array_push($this->selectedItems, $currentItem);
        }

        $this->dispatch(
            'resetSelect',
            id: 'item-select',
        );
        $this->formPurchase->quantityItem = null;
    }

    #[On('destroy-item')]
    public function destroy($id, ItemService $itemService)
    {

        $currentItem = $itemService->getById($id)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item el cuál se desea eliminar');
            return;
        }

        $existingItemIndex = array_search($id, array_column($this->selectedItems, 'id'));
        if ($existingItemIndex !== false) {
            unset($this->selectedItems[$existingItemIndex]);
            $this->selectedItems = array_values($this->selectedItems);
        }
    }

    public function destroyAlertPurchase($id, $name)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            type: 'warning',
            title: 'Usuario',
            message: "¿estás seguro de eliminar el item {$name} de la orden de compra?",
            emit: 'destroy-item',
        );
    }

    public function storeHeader()
    {
        try {
            // Validar los campos de la cabecera
            $this->validate([
                'currentDate' => 'required|date',
                'contractor_name' => 'required|string',
                'contractor_nit' => 'required|string',
                'responsible_name' => 'required|string',
                'company_name' => 'required|string',
                'company_nit' => 'required|string',
                'phone' => 'required|string',
                'material_destination' => 'required|string',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'bank_name' => 'required|string',
                'account_type' => 'required|string',
                'account_number' => 'required|string',
                'support_type_id' => 'required|exists:payment_support,id',
            ]);
    
            InvoiceHeader::create([
                'date' => $this->currentDate,
                'contractor_name' => $this->contractor_name,
                'contractor_nit' => $this->contractor_nit,
                'responsible_name' => $this->responsible_name,
                'company_name' => $this->company_name,
                'company_nit' => $this->company_nit,
                'phone' => $this->phone,
                'material_destination' => $this->material_destination,
                'payment_method_id' => $this->payment_method_id,
                'bank_name' => $this->bank_name,
                'account_type' => $this->account_type,
                'account_number' => $this->account_number,
                'support_type_id' => $this->support_type_id,
            ]);
    
            // Limpiar los campos después de guardar
            $this->reset([
                'currentDate', 'contractor_name', 'contractor_nit', 'responsible_name', 
                'company_name', 'company_nit', 'phone', 'material_destination', 
                'payment_method_id', 'bank_name', 'account_type', 'account_number', 'support_type_id'
            ]);
    
            session()->flash('message', 'La cabecera se ha almacenado exitosamente.');
    
        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrarla
            Log::error('Error al guardar la cabecera: ' . $e->getMessage());
    
            session()->flash('error', 'Ocurrió un error al guardar la cabecera.');
        }
    }
    


}

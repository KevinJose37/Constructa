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

class CreatePurchaseOrder extends Component
{

    public $currentSelect;
    public $currentDate;
    public $selectedItems = [];
    public PurchaseOrderForm $formPurchase;

    public function mount($id)
    {
        $this->currentDate = now()->format('d/m/y');
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
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = number_format(Helpers::calculateTotalIva($currentItem["totalPrice"]), 2, '.', ',');

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
}

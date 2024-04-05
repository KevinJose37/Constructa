<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use App\Services\ItemService;
use App\Models\PaymentSupport;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\PurchaseOrderForm;

class CreatePurchaseOrder extends Component
{

    public $currentSelect;
    public $currentDate;
    public $selectedItems = [];
    public $totalPurchaseIva;
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

        return view('livewire.create-purchase-order', compact('paymentMethods', 'paymentSupport', 'items'));
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
            $this->selectedItems[$existingItemIndex]["iva"] = $this->formatCurrency(Helpers::calculateIva($currentItem["price"]));
            $this->selectedItems[$existingItemIndex]["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["price"]));
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["totalPrice"]));
            $this->totalPurchaseIva += str_replace(',', '', $this->selectedItems[$existingItemIndex]["totalPriceIva"]);
        } else {
            $currentItem["quantity"] = $this->formPurchase->quantityItem;
            $currentItem["totalPrice"] = $this->formatCurrency($currentItem["price"] * $currentItem["quantity"]);
            $currentItem["iva"] = $this->formatCurrency(Helpers::calculateIva($currentItem["price"]));
            $currentItem["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["price"]));
            $currentItem["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["totalPrice"]));
            $this->totalPurchaseIva += str_replace(',', '', $currentItem["totalPriceIva"]);
            array_push($this->selectedItems, $currentItem);
        }

        $this->dispatch(
            'resetSelect',
            id: 'item-select',
        );
        $this->formPurchase->quantityItem = null;
        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
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
            $this->totalPurchaseIva -= str_replace(',', '', $this->selectedItems[$existingItemIndex]["totalPriceIva"]);
            unset($this->selectedItems[$existingItemIndex]);
            $this->selectedItems = array_values($this->selectedItems);
            // Recalculamos para evitar errores
            $this->totalPurchaseIva = 0;
            foreach ($this->selectedItems as $item) {
                $this->totalPurchaseIva += str_replace(',', '', $item["totalPriceIva"]);
            }
        }

        if (count($this->selectedItems) === 0) {
            $this->totalPurchaseIva = 0;
        }

        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
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

    protected function formatCurrency($value)
    {
        return number_format($value, 2, '.', ',');
    }

}

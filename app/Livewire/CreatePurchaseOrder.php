<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Livewire\Forms\CreatePurchaseOrderModalForm;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use App\Services\ItemService;
use App\Models\PaymentSupport;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\PurchaseOrderForm;
use App\Models\InvoiceHeader;


class CreatePurchaseOrder extends Component
{

    public $selectedItems = [];
    public $totalPurchaseIva;
    public PurchaseOrderForm $formPurchase;
    public $currentDate, $contractor_name, $contractor_nit, $responsible_name, $company_name,
        $company_nit, $phone, $material_destination, $payment_method_id, $bank_name,
        $account_type, $account_number, $support_type_id, $lastInvoiceId;

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
        return view('livewire.create-purchase-order', compact('paymentMethods', 'paymentSupport'));
    }


    #[On('storeItem')]
    public function store(ItemService $itemService, $idItem, $unitPrice, $quantityItem)
    {

        if ($idItem === null || empty($idItem)) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'Ingrese un valor válido');
            return;
        }

        $currentItem = $itemService->getById($idItem)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item actual');
            return;
        }

        $existingItemIndex = false;
        foreach ($this->selectedItems as $index => $item) {
            if ($item['id'] == $idItem && $item['price'] == $unitPrice) {
                $existingItemIndex = $index;
                break;
            }
        }
        
        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]["quantity"] += $quantityItem; 
            $this->selectedItems[$existingItemIndex]["totalPrice"] = $this->selectedItems[$existingItemIndex]["price"] * $this->selectedItems[$existingItemIndex]["quantity"];
            $this->selectedItems[$existingItemIndex]["iva"] = $this->formatCurrency(Helpers::calculateIva($this->selectedItems[$existingItemIndex]["price"]));
            $this->selectedItems[$existingItemIndex]["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["price"]));
            $this->selectedItems[$existingItemIndex]["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($this->selectedItems[$existingItemIndex]["totalPrice"]));
            $this->totalPurchaseIva += str_replace(',', '', $this->selectedItems[$existingItemIndex]["totalPriceIva"]);
        } else {
            $currentItem["quantity"] = $quantityItem;
            $currentItem["price"] = $unitPrice;
            $currentItem["totalPrice"] = $this->formatCurrency($unitPrice * $currentItem["quantity"]);
            $currentItem["iva"] = $this->formatCurrency(Helpers::calculateIva($unitPrice));
            $currentItem["priceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($unitPrice));
            $currentItem["totalPriceIva"] = $this->formatCurrency(Helpers::calculateTotalIva($currentItem["totalPrice"]));
            $this->totalPurchaseIva += str_replace(',', '', $currentItem["totalPriceIva"]);
            array_push($this->selectedItems, $currentItem);
        }

        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
    }

    #[On('destroy-item')]
    public function destroy($id, $secondparameters, ItemService $itemService)
    {
        $currentItem = $itemService->getById($id)->toArray();
        if ($currentItem === null) {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'No se encontró información del item el cuál se desea eliminar');
            return;
        }

        $existingItemIndex = $secondparameters;

        if ($existingItemIndex !== false) {
            $this->totalPurchaseIva -= str_replace(',', '', $this->selectedItems[$existingItemIndex]["totalPriceIva"]);
            unset($this->selectedItems[$existingItemIndex]);
            $this->selectedItems = array_values($this->selectedItems);
            // Recalculamos para evitar errores
            $this->totalPurchaseIva = 0;
            foreach ($this->selectedItems as $item) {
                $this->totalPurchaseIva += str_replace(',', '', $item["totalPriceIva"]);
            }
        } else {
            $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: 'Ocurrió un error encontrando el índice del elemento');
            return;
        }

        if (count($this->selectedItems) === 0) {
            $this->totalPurchaseIva = 0;
        }

        $this->totalPurchaseIva = $this->formatCurrency($this->totalPurchaseIva);
    }

    public function destroyAlertPurchase($id, $name, $index)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            secondparameters: $index,
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

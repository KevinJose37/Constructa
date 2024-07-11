<?php

namespace App\Livewire;

use App\Helpers\Helpers;
use Livewire\Component;
use App\Services\ItemService;
use App\Livewire\Forms\CreatePurchaseOrderModalForm;

class CreatePurchaseOrderModal extends Component
{

    public CreatePurchaseOrderModalForm $orderForm;

    public function render(ItemService $itemService)
    {
        $this->orderForm->itemSelect = null;
        $items = $itemService->getAll();
        return view('livewire.create-purchase-order-modal', compact('items'));
    }

    public function getUnit(ItemService $itemService)
    {
        if ($this->orderForm->currentSelect != null) {
            $this->orderForm->itemSelect = $itemService->getById($this->orderForm->currentSelect);
            $this->orderForm->unit = $this->orderForm->itemSelect->unit_measurement;
            $this->orderForm->code = $this->orderForm->itemSelect->cod;
        }
    }

    public function setTotal()
    {
        $this->orderForm->validate();
        $quantityItem = $this->orderForm->quantityItem;
        $priceUnit = $this->orderForm->priceUnit;
        $priceUnit = str_replace(',', '.', str_replace('.', '', $priceUnit));
        if (!is_null($quantityItem) && !is_null($priceUnit) && !is_null($this->orderForm->currentIva)) {
            $total = $priceUnit * $quantityItem;
            $this->orderForm->totalPrice = number_format($total, 2, ',', '.');
            $this->orderForm->totalPriceIva = number_format(Helpers::calculateTotalIva($total, $this->orderForm->currentIva), 0, ',', '.');
        }
    }

    public function save()
    {
        $this->orderForm->validate();
        $this->dispatch('storeItem', $this->orderForm->currentSelect, $this->orderForm->priceUnit, $this->orderForm->quantityItem, $this->orderForm->currentIva)->to(CreatePurchaseOrder::class);
        $this->orderForm->reset();
        $this->dispatch(
            'resetSelect',
            id: 'item-select',
        );
    }
}

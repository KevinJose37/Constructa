<?php

namespace App\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\InvoiceHeader;

class EditPurchaseOrderInfo extends Component
{
    public $orderId;
    public $has_support;
    public $payment_date;
    public $payer;
    public $is_petty_cash;

    protected $listeners = ['setOrderId'];

    public function mount($orderId = null)
    {
        if ($orderId) {
            $this->loadOrder($orderId);
        }
    }

    public function loadOrder($orderId)
    {
        $order = InvoiceHeader::findOrFail($orderId);
        $this->orderId = $order->id;
        $this->has_support = $order->has_support;
        $this->payment_date = $order->payment_date;
        $this->payer = $order->payer;
        $this->is_petty_cash = $order->is_petty_cash;
    }

    public function setOrderId($orderId)
    {
        $this->loadOrder($orderId);
    }

    public function save()
    {
        $order = InvoiceHeader::findOrFail($this->orderId);
        $order->has_support = $this->has_support;
        $order->payment_date = $this->payment_date;
        $order->payer = $this->payer;
        $order->is_petty_cash = $this->is_petty_cash;
        $order->save();

        $this->dispatch('hide-modal');

        session()->flash('message', 'Información actualizada con éxito.');
    }

    public function render()
    {
        return view('livewire.purchaseorder.edit-purchase-order-info');
    }
}

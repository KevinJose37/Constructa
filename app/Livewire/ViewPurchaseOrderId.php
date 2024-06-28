<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaymentMethod;
use App\Models\PaymentSupport;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\PurchaseOrderServices;

class ViewPurchaseOrderId extends Component
{

    public $currentOrder, $paymentMethodName, $paymentSupport;
    public function mount($id, PurchaseOrderServices $servicePurchase)
    {
        if (!is_numeric($id)) {
            $this->redirect('/purchaseorder');
            return;
        }

        $order = $servicePurchase->getById($id);
        if (!$order) {
            $this->redirect('/purchaseorder');
            return;
        }
        $this->currentOrder = $order;
        $this->paymentMethodName = PaymentMethod::find($this->currentOrder->payment_method_id)->payment_name;
        $this->paymentSupport = PaymentSupport::find($this->currentOrder->support_type_id)->support_name;
    }

    public function goBack()
    {
        $this->redirect('/purchaseorder');
    }

    #[Layout('layouts.app')]
    #[Title('Orden de compra')]
    public function render()
    {
        return view('livewire.view-purchase-order-id');
    }
}

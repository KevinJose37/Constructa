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
    public $order;
    public $paymentMethodName;
    public $paymentSupport;
    public $isViewMode = true;

    // Los siguientes campos son necesarios para la vista, aunque no sean editables
    public $contractor_name;
    public $contractor_nit;
    public $responsible_name;
    public $currentDate;
    public $retencionPercentage;
    public $company_name;
    public $company_nit;
    public $phone;
    public $material_destination;
    public $payment_method_id;
    public $bank_name;
    public $account_type;
    public $account_number;
    public $support_type_id;
    public $general_observations;
    public $order_name;

    public function mount($id, PurchaseOrderServices $servicePurchase)
    {
        if (!is_numeric($id)) {
            $this->redirect('/purchaseorder');
            return;
        }

        $this->order = $servicePurchase->getById($id);
        if (!$this->order) {
            $this->redirect('/purchaseorder');
            return;
        }

        // Cargar datos relacionados
        $this->paymentMethodName = PaymentMethod::find($this->order->payment_method_id)->payment_name;
        $this->paymentSupport = PaymentSupport::find($this->order->support_type_id)->support_name;

        // Cargar datos del encabezado para mostrar en la vista
        $this->order_name = $this->order->order_name;
        $this->contractor_name = $this->order->contractor_name;
        $this->contractor_nit = $this->order->contractor_nit;
        $this->responsible_name = $this->order->responsible_name;
        $this->currentDate = $this->order->created_at->format('Y-m-d');
        $this->retencionPercentage = $this->order->retention_value;
        $this->company_name = $this->order->company_name;
        $this->company_nit = $this->order->company_nit;
        $this->phone = $this->order->phone;
        $this->material_destination = $this->order->material_destination;
        $this->payment_method_id = $this->order->payment_method_id;
        $this->bank_name = $this->order->bank_name;
        $this->account_type = $this->order->account_type;
        $this->account_number = $this->order->account_number;
        $this->support_type_id = $this->order->support_type_id;
        $this->general_observations = $this->order->general_observations;
    }

    public function goBack()
    {
        $this->redirect('/purchaseorder');
    }

    #[Layout('layouts.app')]
    #[Title('Visualizar orden de compra')]
    public function render()
    {
        return view('livewire.purchase-order-form');
    }
}

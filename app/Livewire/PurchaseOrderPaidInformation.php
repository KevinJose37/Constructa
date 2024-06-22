<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvoiceHeader;
use Illuminate\Support\Facades\Log;
use App\Services\PurchaseOrderServices;
use App\Livewire\Forms\PurchaseOrderPaidInformationForm;

class PurchaseOrderPaidInformation extends Component
{

    public InvoiceHeader $order;
    public PurchaseOrderPaidInformationForm $form;

    public function mount(InvoiceHeader $order)
    {
        $this->order = $order;
        if($this->order->paidInformation != null){
            $this->form->name = $this->order->paidInformation->payment_person;
            $this->form->method = $this->order->paidInformation->payment_method;
            $this->form->how = $this->order->paidInformation->payment_how;
            $this->form->date = $this->order->paidInformation->payment_date;
        }
    }


    public function save(PurchaseOrderServices $purchaseService)
    {
        $this->form->validate();
        $data['payment_person'] = $this->form->name;
        $data['payment_method'] = $this->form->method;
        $data['payment_how'] = $this->form->how;
        $data['payment_date'] = $this->form->date;
        $responseSave = $purchaseService->UpdateCreatePaid($data, $this->order->id);
        if (!is_array($responseSave) && !isset($responseSave['success'])) {
            $this->form->reset();
            $this->mount($purchaseService->getById($this->order->id));
            $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
            $this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: "Se asignó correctamente la información de pago");
            return;
        }
        $this->dispatch('alert', type: 'error', title: 'Órdenes de compra', message: "Ocurrió un error al asignar la información de pago");
    }

    public function render(){
        // dd($this->order);
        return view('livewire.purchase-order-paid-information');
    }
}

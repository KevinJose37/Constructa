<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvoiceHeader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PurchaseOrderServices;
use App\Livewire\Forms\PurchaseOrderPaidInformationForm;

class PurchaseOrderPaidInformation extends Component
{

    public InvoiceHeader $order;
    public PurchaseOrderPaidInformationForm $form;

    public function mount(InvoiceHeader $order)
    {
        $this->order = $order;
        if (!is_null($this->order->paidInformation)) {
            $this->form->name = $this->order->paidInformation->payment_person;
            $this->form->method = $this->order->paidInformation->payment_method;
            $this->form->how = $this->order->paidInformation->payment_how;
            $this->form->date = $this->order->paidInformation->payment_date;
            $this->form->approved_tech = $this->order->paidInformation->approved_tech;
            $this->form->approved_account = $this->order->paidInformation->approved_account;
        }
    }
    public function approveTech(PurchaseOrderServices $purchaseService)
    {
        if (!Auth::user()->hasRole('Empleado') && !Auth::user()->hasRole('Contador')) {
            $this->validate([
                'form.approved_tech' => 'required', // Asegúrate de que el campo esté presente y válido
            ]);

            // Convertir directamente a booleano si es necesario
            $approvedTech = (bool) $this->form->approved_tech;
            $purchaseService->updateTechDecision($approvedTech, $this->order->id);
            $this->form->open = false;
            $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
            $this->mount($purchaseService->getById($this->order->id));
            $this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: "Se actualizó correctamente la orden de compra");
            return;
        } else {
            abort(403, 'No autorizado.');
        }
    }

    public function approveAccount(PurchaseOrderServices $purchaseService)
    {
        if (Auth::user()->hasRole('Contador')) {
            $this->validate([
                'form.approved_account' => 'required',
            ]);

            $approvedAccount = (bool) $this->form->approved_account;
            $purchaseService->updateAccountDecision($approvedAccount, $this->order->id);
            $this->form->open = false;
            $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
            $this->mount($purchaseService->getById($this->order->id));
            $this->dispatch('alert', type: 'success', title: 'Órdenes de compra', message: "Se actualizó correctamente la orden de compra");
            return;
        } else {
            abort(403, 'No autorizado.');
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

    public function render()
    {
        return view('livewire.purchase-order-paid-information');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\InvoiceHeader;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\PurchaseOrderServices;

class QueryPurchaseOrder extends Component
{

    public $search = "";

    #[Layout('layouts.app')]
    #[Title('Órdenes de compra')]
    #[On('purchaseRefresh')]
    public function render(PurchaseOrderServices $purchaseOrderServices)
    {
        $purchaseOrder = $purchaseOrderServices->getAllPaginate($this->search);
        return view('livewire.query-purchase-order', compact("purchaseOrder"));
    }

    #[On('destroy-purchase')]
    public function destroy($id, PurchaseOrderServices $purchaseOrderServices)
    {
        $deleteProject = $purchaseOrderServices->Delete($id);
        if ($deleteProject === true) {
            $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
            $this->dispatch('alert', type: 'success', title: 'Proyectos', message: "Se eliminó correctamente el proyecto");
            return;
        }

        $message = $deleteProject['message'];
        $this->dispatch('alert', type: 'error', title: 'Proyectos', message: $message);
    }

    public function destroyAlert($id, $name)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            type: 'warning',
            title: 'Orden de compra',
            message: "¿estás seguro de eliminar la orden de compra para el proyecto {$name}?",
            emit: 'destroy-purchase',
        );
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\ConsolidatedServices;
use Livewire\WithPagination;

class QueryConsolidated extends Component
{
    use WithPagination;

    public $search = "";
    public $totalPayable = 0;
    public $totalPriceIva = 0;

    #[Layout('layouts.app')]
    #[Title('Consolidado')]
    #[On('purchaseRefresh')]
    public function render(ConsolidatedServices $consolidatedServices)
    {
        $purchaseOrder = $consolidatedServices->getFilteredDetails($this->search);

        // Calculate the total payable for the filtered orders
        $this->totalPriceIva = $purchaseOrder->sum(function ($order) {
            return $order->invoiceDetails->sum('total_price_iva');
        });

        // Calculate the total price included IVA for the filtered orders
        $this->totalPayable = $purchaseOrder->sum(function ($order) {
            return $order->total_payable;
        });

        return view('livewire.view-consolidated', compact('purchaseOrder'));
    }
}

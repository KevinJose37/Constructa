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
    public $project_id;
	public $project;

    public function mount($id)
    {
        $this->project_id = $id;
		$this->project = \App\Models\Project::find($id);
    }

    #[Layout('layouts.app')]
    #[Title('Consolidado')]
    #[On('purchaseRefresh')]
    public function render(ConsolidatedServices $consolidatedServices)
    {
        $purchaseOrder = $consolidatedServices->getFilteredDetailsByProject($this->search, $this->project_id);

        // Calculate the total price included IVA for the filtered orders
        $this->totalPriceIva = $purchaseOrder->sum(function ($order) {
            return $order->invoiceDetails->sum('total_price_iva');
        });

        // Calculate the total payable for the filtered orders
        $this->totalPayable = $this->totalPriceIva - $purchaseOrder->sum(function ($order) {
            return $order->retention;
        });

        return view('livewire.view-consolidated', compact('purchaseOrder'));
    }
}

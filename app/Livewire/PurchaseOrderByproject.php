<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\ProjectServices;
use App\Services\PurchaseOrderServices;

class PurchaseOrderByproject extends Component
{

    use WithPagination;
    public $projectId;
    public ?Project $project ;
    public $search = "";

    public function mount($id, ProjectServices $projectServices)
    {
        if (!is_numeric($id) )  {
            $this->redirect('/purchaseorder');
            return;
        }

        $this->project = $projectServices->getById($id);
        if(!$this->project){
            $this->redirect('/purchaseorder');
            return;
        }

        $this->projectId = $id;
        
    }

    #[Layout('layouts.app')]
    #[Title('Orden de compra')]
    public function render(PurchaseOrderServices $servicePurchase)
    {
        $purchaseOrder = $servicePurchase->getByProject($this->projectId, $this->search);
        
        return view('livewire.purchase-order-byproject', compact('purchaseOrder'));
    }

    #[On('destroy-purchase-project')]
    public function destroy($id, PurchaseOrderServices $purchaseOrderServices)
    {
        $deleteProject = $purchaseOrderServices->Delete($id);
        if ($deleteProject === true) {
            $this->dispatch('purchaseRefresh')->to(PurchaseOrderByproject::class);
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
            emit: 'destroy-purchase-project',
        );
    }
}

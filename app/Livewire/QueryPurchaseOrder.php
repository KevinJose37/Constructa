<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\InvoiceHeader;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

use App\Services\ProjectServices;
use App\Services\PurchaseOrderServices;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class QueryPurchaseOrder extends Component
{

    use WithPagination;

    public $search = "";
    public $projectId = null;
    public ?Project $project = null;
    public $isProjectFiltered = false;

    public function mount($projectId  = null, ProjectServices $projectServices = null)
    {
        // Si se pasa un parámetro de proyecto
        if ($projectId  !== null) {
            // Validar que sea numérico
            if (!is_numeric($projectId)) {
                return $this->redirect('/purchaseorder');
            }

            // Obtener el proyecto
            $this->project = $projectServices->getById($projectId);
            if (!$this->project) {
                return $this->redirect('/purchaseorder');
            }

            $this->projectId = $projectId;
            $this->isProjectFiltered = true;
        }
    }

    #[Layout('layouts.app')]
    #[Title('Órdenes de compra')]
    #[On('purchaseRefresh')]
    public function render(PurchaseOrderServices $purchaseOrderServices)
    {
        // Determinar qué método usar según si hay filtro de proyecto
        if ($this->isProjectFiltered) {
            $purchaseOrder = $purchaseOrderServices->getByProject($this->projectId, $this->search);
        } else {
            $purchaseOrder = $purchaseOrderServices->getAllPaginate($this->search);
        }

        return view('livewire.query-purchase-order', compact('purchaseOrder'));
    }


    #[On('destroy-purchase')]
    public function destroy($id, PurchaseOrderServices $purchaseOrderServices)
    {
        $deleteProject = $purchaseOrderServices->Delete($id);
        if ($deleteProject === true) {
            $this->dispatch('purchaseRefresh')->to(QueryPurchaseOrder::class);
            $this->dispatch('alert', type: 'success', title: 'Proyectos', message: "Se eliminó correctamente la orden de compra");
            return;
        }

        $message = $deleteProject['message'];
        $this->dispatch('alert', type: 'error', title: 'Proyectos', message: $message);
    }

    public function destroyAlert($id)
    {
        $this->dispatch(
            'alertConfirmation',
            id: $id,
            type: 'warning',
            title: 'Orden de compra',
            message: "¿estás seguro de eliminar la orden de compra para el proyecto?",
            emit: 'destroy-purchase',
        );
    }
}

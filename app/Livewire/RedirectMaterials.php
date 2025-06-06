<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Models\RealProject;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Models\RealProjectInfo;
use Livewire\Attributes\Layout;
use App\Models\MaterialRedirections;
use App\Services\PurchaseOrderServices;

class RedirectMaterials extends Component
{
    public $purchaseOrder;
    public $chapterSelections = []; // Índice => id del capítulo seleccionado
    public $itemSelections = [];     // Índice => id del ítem seleccionado
    public $availableItems = [];     // Índice => lista de ítems disponibles por capítulo

    private PurchaseOrderServices $purchase;

    public function mount(int $id, PurchaseOrderServices $purchase)
    {
        $this->purchase = $purchase;

        $purchaseOrder = $this->purchase->getById($id);
        if (!$purchaseOrder) {
            $this->redirect('/purchaseorder');
            return;
        }

        $this->purchaseOrder = $purchaseOrder;

        foreach ($purchaseOrder->invoiceDetails as $index => $detail) {
            $this->chapterSelections[$index] = null;
            $this->itemSelections[$index] = null;
            $this->availableItems[$index] = [];
        }
    }

    public function updatedChapterSelections($value, $key)
    {
        $items = RealProjectInfo::where('real_project_id', $value)->get();
        $this->availableItems[$key] = $items;
        $this->itemSelections[$key] = null;
    }

    public function saveRedirections()
    {
        try {
            foreach ($this->chapterSelections as $index => $chapterId) {
                $itemId = $this->itemSelections[$index] ?? null;
                if (!$chapterId || !$itemId) {
                    $this->dispatch('alert', type: 'error', title: 'Error', message: 'Todos los materiales deben tener un capítulo y un ítem seleccionados.');
                    return;
                }

                MaterialRedirections::create([
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'invoice_detail_id' => $this->purchaseOrder->invoiceDetails[$index]->id,
                    'chapter_id' => $chapterId,
                    'item_id' => $itemId,
                ]);

                RealProjectInfo::where('id', $itemId)->update([
                    'total' => $this->purchaseOrder->invoiceDetails[$index]->total_price_iva
                ]);
            }

            $this->dispatch('alert', type: 'success', title: 'Éxito', message: 'Redirecciones de materiales guardadas correctamente.');
            $this->redirect(route('purchaseorder.view', ['projectId' => $this->purchaseOrder->project_id]));
        } catch (\Throwable $th) {
            $this->dispatch('alert', type: 'error', title: 'Error', message: 'Ocurrió un error al guardar las redirecciones de materiales: ' . $th->getMessage());
        }
    }


    #[Layout('layouts.app')]
    #[Title('Redireccion de materiales')]
    public function render()
    {
        $chapters = RealProject::where('project_id', $this->purchaseOrder->project_id)->get();
        return view('livewire.redirect-materials', compact('chapters'));
    }
}
